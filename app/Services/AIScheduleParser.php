<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIScheduleParser
{
    protected $apiKey;

    public function __construct()
    {
        // Use the new Gemini key, fallback to vision key if not yet renamed
        $this->apiKey = config('services.google.gemini_key');
    }

    /**
     * Send an image to Gemini 1.5 Flash API and extract schedule data.
     */
    public function parseScheduleImage($imagePath)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key is missing. Please add GEMINI_API_KEY to your .env file.');
        }

        $fullPath = public_path($imagePath);
        if (!file_exists($fullPath)) {
            throw new \Exception("Schedule image not found at: {$fullPath}");
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

        try {
            $prompt = "Extract the official work schedule from this image. 
            Return a JSON array of objects, where each object has:
            - 'day_of_week': (e.g., 'Monday', 'Tuesday')
            - 'start_time': (e.g., '08:00 AM')
            - 'end_time': (e.g., '05:00 PM')
            
            Only include lines that represent actual work shifts. 
            Format times strictly as HH:MM AM/PM. 
            If a day has multiple shifts, create separate entries for each.";

            $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generation_config' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                $errorMessage = $response->json('error.message') ?? 'Unknown error';
                
                if (str_contains($errorMessage, 'billing')) {
                    throw new \Exception('AI Scan failed: This API requires billing or a valid API Key. Please ensure you are using a Gemini API Key from Google AI Studio.');
                }
                
                throw new \Exception('AI Scan failed: ' . $errorMessage);
            }

            $result = $response->json('candidates.0.content.parts.0.text');
            
            if (!$result) {
                Log::warning('Gemini returned empty result for image: ' . $imagePath);
                return [];
            }

            $schedules = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON parsing failed: ' . json_last_error_msg());
                return [];
            }

            return $schedules;

        } catch (\Exception $e) {
            Log::error('Gemini Schedule Parsing Failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
