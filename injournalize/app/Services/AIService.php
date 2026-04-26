<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\JournalEntry;

class AIService
{
    public function chat($message)
    {
        try {
            $journals = JournalEntry::latest()->get();

            if ($journals->isEmpty()) {
                return "No journals found.";
            }

            $journalData = $journals->map(function ($j) {
                return [
                    "title" => $j->title,
                    "content" => $j->content,
                    "location" => $j->location,
                    "mood" => $j->mood,
                    "date" => $j->created_at->format('Y-m-d')
                ];
            })->values()->toArray();

            $systemPrompt = "
You are a helpful journal assistant.

You have access to journal data.

Behavior:
- If user greets (hello, hi, hey) → respond naturally.
- If user asks about journals → answer using the data.
- If question is unrelated → say politely you only handle journal-related queries.

Rules:
- 'how many journals' → return ONLY the number
- 'titles' → list titles (one per line)
- 'dates' → list dates
- Be clear and natural

DATA:
" . json_encode($journalData, JSON_UNESCAPED_UNICODE);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                "model" => "llama-3.1-8b-instant",
                "temperature" => 0.3,
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $systemPrompt
                    ],
                    [
                        "role" => "user",
                        "content" => $message
                    ]
                ]
            ]);

            if (!$response->successful()) {
                return "Groq API error.";
            }

            $reply = $response['choices'][0]['message']['content'] ?? null;

            return $reply ? trim($reply) : "Sorry, I couldn't answer that.";

        } catch (\Exception $e) {
            return "AI error.";
        }
    }
}