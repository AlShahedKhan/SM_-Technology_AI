<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $queueName;

    public function __construct(string $queueName)
    {
        $this->queueName = $queueName;
    }

    public function handle(): array
    {
        $aiUrl = config('services.ai.url');
        $response = Http::post($aiUrl, [
            'query' => $this->sanitizeText($this->queueName),
        ]);

        Log::info('AI raw response: ' . $response->body());

        // Step 1: Decode the outer JSON (like: { "answer": "...." })
        $decoded = json_decode($response->body(), true);

        // Step 2: Extract message if it exists
        $aiText = is_array($decoded) && isset($decoded['answer'])
            ? $decoded['answer']
            : $response->body(); // fallback if format is unexpected

        // Step 3: Sanitize
        $cleaned = $this->sanitizeText($aiText);

        Log::info('Sanitized AI text: ' . $cleaned);

        // Step 4: Split into words
        return $this->chunkResponse($cleaned);
    }



    // private function sanitizeText(string $text): string
    // {
    //     $text = strip_tags($text);
    //     $text = preg_replace('/\\\n|\\\t/', '', $text);
    //     $text = preg_replace('/\s+/', ' ', $text);
    //     return trim($text);
    // }
    private function sanitizeText(string $text): string
    {
        $text = strip_tags($text);
        $text = preg_replace([
            '/\\\\n\\\\n/',
            '/\\\\n-/',
            '/\\\\n/',
            '/\\\\t/',
            '/\\\\/',
            '/"answer":\\\\?/'
        ], '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }


    private function chunkResponse(string $text): array
    {
        // Breaks by whitespace while preserving punctuation
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

}
