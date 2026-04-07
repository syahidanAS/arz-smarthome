<?php

namespace App\Http\Controllers;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

use Illuminate\Http\Request;

class MqttController extends Controller
{
    public function publish(Request $request)
    {
        $server   = env('MQTT_HOST', '192.168.88.13');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'laravel-mqtt-client';

        $mqtt = new MqttClient($server, $port, $clientId);

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USERNAME', 'idan'))
            ->setPassword(env('MQTT_PASSWORD', ''));

        $mqtt->connect($connectionSettings, true);

        $mqtt->publish($request->topic, $request->message, 0);

        $mqtt->disconnect();

        return response()->json([
            'status' => 'success',
            'message' => 'Message published to MQTT topic'
        ], 200);
    }
}
