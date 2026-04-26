<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;
use App\Services\PromptService;
use App\Services\JournalContextService;
use App\Services\JournalCrudService;

class ChatBotController extends Controller
{
    // 💬 INQUIRY MODE — read only
    public function send(
        Request $request,
        AIService $ai,
        PromptService $prompt,
        JournalContextService $context
    ) {
        try {
            $message = $request->input('message');
            $history = array_slice($request->input('history', []), -10);

            $contextData = $context->resolve($message);
            $fullPrompt = $prompt->chatPrompt($message, $contextData, $history);
            $response = $ai->chat($fullPrompt);

            return response()->json(['reply' => $response]);

        } catch (\Gemini\Exceptions\ErrorException $e) {
            return response()->json(['reply' => 'Rate limit reached, please wait a moment and try again.']);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ✏️ CRUD MODE — create, update, delete
    public function crud(
        Request $request,
        AIService $ai,
        PromptService $prompt,
        JournalContextService $context,
        JournalCrudService $crud
    ) {
        try {
            $message = $request->input('message');
            $history = array_slice($request->input('history', []), -10);
            $pendingOperation = $request->input('pending_operation');

            // User confirmed a pending destructive operation
            if ($pendingOperation) {
                return $this->executePendingOperation($pendingOperation, $crud);
            }

            $contextData = $context->resolve($message);
            $fullPrompt = $prompt->crudPrompt($message, $contextData, $history);
            $response = $ai->chat($fullPrompt);

            // Clean response — strip markdown backticks if any
            $trimmed = trim(preg_replace('/```json|```/', '', $response));

            if (str_starts_with($trimmed, '{')) {
                $decoded = json_decode($trimmed, true);

                if ($decoded && isset($decoded['operation'])) {
                    $operation = $decoded['operation'];

                    // Just chatting in CRUD mode
                    if ($operation === 'NONE') {
                        return response()->json(['reply' => $decoded['message']]);
                    }

                    // Need more info
                    if ($operation === 'NEED_INFO') {
                        return response()->json(['reply' => $decoded['message']]);
                    }

                    // CREATE — execute immediately
                    if ($operation === 'CREATE') {
                        $entry = $crud->create($decoded['title'], $decoded['content']);
                        return response()->json([
                            'reply' => "✅ Created new entry!\n\n**Title:** {$entry->title}\n**Content:** {$entry->content}",
                            'reload' => true
                        ]);
                    }

                    // UPDATE — ask for confirmation
                    if ($operation === 'UPDATE') {
                        $msg = "⚠️ Are you sure you want to update entry #{$decoded['id']}?";
                        if (isset($decoded['title'])) $msg .= "\n**New title:** {$decoded['title']}";
                        if (isset($decoded['content'])) $msg .= "\n**New content:** {$decoded['content']}";
                        $msg .= "\n\nReply **yes** to confirm or **no** to cancel.";

                        return response()->json([
                            'reply' => $msg,
                            'pending_operation' => $decoded
                        ]);
                    }

                    // DELETE — ask for confirmation
                    if ($operation === 'DELETE') {
                        return response()->json([
                            'reply' => "⚠️ Are you sure you want to delete entry #{$decoded['id']}? This cannot be undone.\n\nReply **yes** to confirm or **no** to cancel.",
                            'pending_operation' => $decoded
                        ]);
                    }
                }
            }

            // Fallback — return raw response
            return response()->json(['reply' => $response]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['reply' => '❌ Entry not found. Please check the ID and try again.']);
        } catch (\Gemini\Exceptions\ErrorException $e) {
            return response()->json(['reply' => 'Rate limit reached, please wait a moment and try again.']);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function executePendingOperation($operation, JournalCrudService $crud)
    {
        $op = $operation['operation'];

        if ($op === 'UPDATE') {
            $entry = $crud->update($operation['id'], [
                'title' => $operation['title'] ?? null,
                'content' => $operation['content'] ?? null,
            ]);
            return response()->json([
                'reply' => "✅ Updated entry #{$entry->id}!\n\n**Title:** {$entry->title}\n**Content:** {$entry->content}",
                'reload' => true
            ]);
        }

        if ($op === 'DELETE') {
            $title = $crud->delete($operation['id']);
            return response()->json([
                'reply' => "🗑️ Deleted entry: **{$title}**",
                'reload' => true
            ]);
        }

        return response()->json(['reply' => '❌ Unknown operation.']);
    }
}