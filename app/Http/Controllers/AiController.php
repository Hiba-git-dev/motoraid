<?php

namespace App\Http\Controllers;

use App\Services\OpenAiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function __construct(private OpenAiService $openAiService) {}

    public function chat(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:500',
            ]);

            $reply = $this->openAiService->chat($validated['message']);

            return response()->json([
                'reply' => $reply
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur majeure, on renvoie toujours un JSON valide
            return response()->json([
                'reply' => 'Le serveur MotoRaid rencontre une erreur technique. Détails : ' . $e->getMessage()
            ], 500);
        }
    }
}