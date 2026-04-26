<?php
namespace App\Services;

class PromptService
{
    public function chatPrompt($message, $context = null, $history = [])
    {
        $contextBlock = $context ? "\nAPI Data:\n$context\n" : "";

        $historyBlock = "";
        if (!empty($history)) {
            $historyBlock = "\nConversation History:\n";
            foreach ($history as $entry) {
                $role = $entry['role'] === 'user' ? 'User' : 'AI';
                $historyBlock .= "$role: {$entry['content']}\n";
            }
            $historyBlock .= "\n";
        }

        return "
You are a helpful AI assistant for a journal app called InJournalize.
You help users query and understand their journal entries.
Always refer to previous conversation context when answering follow-up questions.
If the user uses pronouns like 'it', 'that', 'those', refer back to conversation history.
Be conversational and friendly.
Do NOT perform any create, update, or delete operations — you are in read-only inquiry mode.

$historyBlock
$contextBlock

Current user message: $message
";
    }

    public function crudPrompt($message, $context = null, $history = [])
    {
        $contextBlock = $context ? "\nAPI Data:\n$context\n" : "";

        $historyBlock = "";
        if (!empty($history)) {
            $historyBlock = "\nConversation History:\n";
            foreach ($history as $entry) {
                $role = $entry['role'] === 'user' ? 'User' : 'AI';
                $historyBlock .= "$role: {$entry['content']}\n";
            }
            $historyBlock .= "\n";
        }

        return "
You are a journal management assistant for InJournalize.
You help users create, update, and delete journal entries.
Always refer to previous conversation context.

When the user wants to perform an operation, respond with ONLY a raw JSON object and nothing else.
No explanation, no markdown, no extra text — ONLY the JSON.

CREATE:
{\"operation\": \"CREATE\", \"title\": \"title here\", \"content\": \"content here\"}

UPDATE:
{\"operation\": \"UPDATE\", \"id\": 1, \"title\": \"new title\", \"content\": \"new content\"}

DELETE:
{\"operation\": \"DELETE\", \"id\": 1}

NEED MORE INFO (if user didn't provide enough details):
{\"operation\": \"NEED_INFO\", \"message\": \"What title and content would you like for the new entry?\"}

Rules:
- Output ONLY raw JSON, no markdown backticks, no explanation
- For CREATE, always ask for title and content if not provided
- For UPDATE/DELETE, always ask for the entry ID if not provided
- If the user is just chatting and not requesting CRUD, respond with:
{\"operation\": \"NONE\", \"message\": \"your conversational response here\"}

$historyBlock
$contextBlock

Current user message: $message
";
    }
}