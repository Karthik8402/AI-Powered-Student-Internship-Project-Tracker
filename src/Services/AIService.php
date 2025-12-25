<?php

namespace App\Services;

use App\Config\Config;

class AIService {
    
    private static $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent';
    
    /**
     * Generate an AI response using Google Gemini 1.5 Flash
     */
    public static function generateResponse($prompt) {
        $apiKey = Config::get('GEMINI_API_KEY');
        
        if (!$apiKey) {
            error_log("Gemini API Key missing");
            return null;
        }

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 300,
            ]
        ];

        $ch = curl_init(self::$apiUrl . '?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('Gemini API Error: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }
        
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($result['candidates'][0]['content']['parts'][0]['text']);
        }
        
        error_log('Gemini API Response Error: ' . json_encode($result));
        return null;
    }

    // Smart suggestion for pre-existing task based on project context
    public static function suggestNextTask($projectDescription, $status = 'pending') {
        // Fallback static suggestions if API fails
        $fallback = self::getFallbackSuggestion($status);

        try {
            $prompt = "You are a helpful project manager AI. Given the project description: '{$projectDescription}' and current status: '{$status}', suggest ONE single, short, complete sentence for the next task. Ensure it is actionable and grammatically complete. Do not use markdown or quotes.";
            
            $suggestion = self::generateResponse($prompt);
            
            if ($suggestion && !preg_match('/[.!?]$/', $suggestion)) {
                 $suggestion .= '.';
            }
            
            return $suggestion ?: $fallback;
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    private static function getFallbackSuggestion($status) {
        $statusSuggestions = [
            'pending' => "Break down project into milestones.",
            'in_progress' => "Review progress and update blockers.",
            'review' => "Prepare documentation for review.",
            'completed' => "Write final project report."
        ];
        return $statusSuggestions[$status] ?? $statusSuggestions['pending'];
    }
}

