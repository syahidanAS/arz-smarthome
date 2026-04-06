<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class WelcomeController extends Controller
{
    public function index()
    {
        $ws_ip = env('WS_IP', 'localhost');
        return view('welcome', compact('ws_ip'));
    }

    public function switchAction(Request $request)
    {
        $request->validate([
            'relay' => 'required|integer',
            'action' => 'required|string|in:on,off',
        ]);

        $relay = $request->input('relay');
        $action = $request->input('action');

        // Endpoint API eksternal
        $url = env('RASPI_IP') . "/relay/{$relay}/{$action}";

        try {
            // Request POST ke API eksternal
            $response = Http::withHeaders([
                'Authorization' => "Bearer " . env('API_TOKEN')
            ])->post($url);

            // Cek status
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Relay {$relay} berhasil di-{$action}"
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Gagal mengubah relay: " . $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ], 500);
        }
    }
}
