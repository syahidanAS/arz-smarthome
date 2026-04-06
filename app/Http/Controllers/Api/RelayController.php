<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RelayController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'relay' => 'required|integer',
            'action' => 'required|string|in:on,off',
        ]);

        $relay = $validated['relay'];
        $action = $validated['action'];

        $baseUrl = config('services.raspi.url'); // best practice
        $token = $request->bearerToken() ?? config('services.raspi.token');

        $url = "{$baseUrl}/relay/{$relay}/{$action}";

        try {
            $response = Http::withToken($token)
                ->timeout(5)
                ->retry(2, 200)
                ->post($url);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Relay {$relay} berhasil di-{$action}",
                    'data' => $response->json()
                ]);
            }

            // kalau gagal (4xx / 5xx)
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah relay',
                'detail' => $response->body()
            ], $response->status());
        } catch (\Throwable $e) {
            Log::error('Relay API error', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
