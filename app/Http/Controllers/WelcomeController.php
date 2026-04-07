<?php

namespace App\Http\Controllers;

use App\Models\Relay;
use Dba\Connection;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class WelcomeController extends Controller
{
    public function index()
    {
        $relays = Relay::pluck('status', 'relay_number');
        return view('welcome', compact('relays'));
    }

    public function switchAction(Request $request)
    {
        $request->validate([
            'relay' => 'required|integer|min:1',
            'action' => 'required|in:on,off',
        ]);

        $server   = env('MQTT_HOST', '192.168.88.13');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'laravel-mqtt-client';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USERNAME', 'idan'))
            ->setPassword(env('MQTT_PASSWORD', ''))
            ->setKeepAliveInterval(60);

        $relay = $request->relay;
        $action = $request->action;
        $topic = "relay/{$relay}";
        $message = $action === 'on' ? 1 : 0;

        try {
            $mqtt->connect($connectionSettings, true);

            $mqtt->publish($topic, $message, 0);

            $mqtt->disconnect();

            Relay::updateOrInsert(
                ['relay_number' => $relay],
                ['status' => $action]
            );

            return response()->json([
                'status' => 'success',
                'relay' => $relay,
                'action' => $action,
                'topic' => $topic,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'relay' => $relay,
                'action' => $action
            ], 500);
        }
    }
}
