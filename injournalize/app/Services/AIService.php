<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;

class AIService
{
    public function chat($message)
    {
        $result = Gemini::generativeModel(
            model: 'gemini-3.1-flash-lite-preview'
        )->generateContent($message);

        return $result->text();
    }
}