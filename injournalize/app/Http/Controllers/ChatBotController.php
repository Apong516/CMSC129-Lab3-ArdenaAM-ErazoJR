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

            // 🔥 FIX: use AIService directly (no PromptService conflict)
            $response = $ai->chat($message);

            // 🔥 CLEAN RESPONSE
            $clean = trim($response);

            // remove markdown if AI wraps JSON
            $clean = preg_replace('/```json|```/', '', $clean);

            $decoded = json_decode($clean, true);

            if (is_array($decoded)) {

                // COUNT
                if (isset($decoded['total_entries'])) {
                    $clean = (string) $decoded['total_entries'];
                }

                // TITLES
                elseif (isset($decoded['titles']) && is_array($decoded['titles'])) {
                    $clean = implode("\n", array_map(function ($item) {
                        return is_array($item) ? implode(", ", $item) : (string) $item;
                    }, $decoded['titles']));
                }

                // DATES
                elseif (isset($decoded['dates']) && is_array($decoded['dates'])) {
                    $clean = implode("\n", array_map(function ($item) {
                        return is_array($item) ? implode(", ", $item) : (string) $item;
                    }, $decoded['dates']));
                }

                // FALLBACK
                else {
                    $clean = json_encode($decoded, JSON_PRETTY_PRINT);
                }
            }

            return response()->json(['reply' => $clean]);

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

            // User confirmed pending action
            if ($pendingOperation) {
                return $this->executePendingOperation($pendingOperation, $crud);
            }

            $contextData = $context->resolve($message);
            $fullPrompt = $prompt->crudPrompt($message, $contextData, $history);
            $response = $ai->chat($fullPrompt);

            $trimmed = trim(preg_replace('/```json|```/', '', $response));

            if (str_starts_with($trimmed, '{')) {
                $decoded = json_decode($trimmed, true);

                if ($decoded && isset($decoded['operation'])) {
                    $operation = $decoded['operation'];

                    if ($operation === 'NONE' || $operation === 'NEED_INFO') {
                        return response()->json(['reply' => $decoded['message']]);
                    }

                    if ($operation === 'CREATE') {
                        $entry = $crud->create($decoded['title'], $decoded['content']);
                        return response()->json([
                            'reply' => "✅ Created new entry!\n\n**Title:** {$entry->title}\n**Content:** {$entry->content}",
                            'reload' => true
                        ]);
                    }

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

                    if ($operation === 'DELETE') {
                        return response()->json([
                            'reply' => "⚠️ Are you sure you want to delete entry #{$decoded['id']}? This cannot be undone.\n\nReply **yes** to confirm or **no** to cancel.",
                            'pending_operation' => $decoded
                        ]);
                    }
                }
            }

            return response()->json(['reply' => $response]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['reply' => '❌ Entry not found.']);
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