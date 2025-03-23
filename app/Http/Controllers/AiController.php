<?php

namespace App\Http\Controllers;

use App\Jobs\AiJob;
use Illuminate\Http\Request;

class AiController extends Controller
{
    // working with postman 3/23/2025
    // public function sendMessage(Request $request)
    // {
    //     $validated = $request->validate([
    //         'query' => 'required|string',
    //     ]);

    //     $message = $validated['query'];

    //     // Get chunked AI response
    //     $aiResponseChunks = (new AiJob($message))->handle();

    //     return response()->json([
    //         'message' => 'Message sent to AI successfully',
    //         'data' => $aiResponseChunks,
    //     ]);
    // }

    // working fine in web 3/23/2025
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string',
        ]);

        $message = $validated['query'];

        // Get AI response (chunked into words)
        $aiResponseChunks = (new AiJob($message))->handle();

        // Get old messages from session or init
        $chat = session('chat', []);

        // Push new entry
        $chat[] = [
            'query' => $message,
            'response' => $aiResponseChunks
        ];

        // Save updated chat back to session
        session(['chat' => $chat]);

        return view('chat', [
            'messages' => $chat,
        ]);
    }




}
