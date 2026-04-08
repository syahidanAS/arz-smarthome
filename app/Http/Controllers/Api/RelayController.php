<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Relay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class RelayController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        $validToken = env('API_TOKEN', 'secret-token');

        if (!$token || $token !== $validToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validasi request
        $validated = $request->validate([
            'relay' => 'required|integer|min:1',
            'action' => 'required|string|in:on,off',
        ]);

        $relay = $validated['relay'];
        $action = $validated['action'];

        // MQTT config dari env
        $server   = env('MQTT_HOST', '192.168.88.13');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'laravel-mqtt-client';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USERNAME', 'idan'))
            ->setPassword(env('MQTT_PASSWORD', ''))
            ->setKeepAliveInterval(60);

        $topic = "relay/{$relay}";
        $message = $action === 'on' ? 1 : 0;

        try {
            // Connect ke MQTT broker
            $mqtt->connect($connectionSettings, true);

            // Publish pesan
            $mqtt->publish($topic, $message, 0);

            // Disconnect
            $mqtt->disconnect();

            // Update database
            Relay::updateOrInsert(
                ['relay_number' => $relay],
                ['status' => $action]
            );

            return response()->json([
                'status' => 'success',
                'message' => "Relay {$relay} berhasil di-{$action}",
                'data' => [
                    'topic' => $topic,
                    'message' => $message
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('MQTT Relay error', [
                'relay' => $relay,
                'action' => $action,
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
