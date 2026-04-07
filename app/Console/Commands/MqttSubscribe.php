<?php

namespace App\Console\Commands;

use App\Models\SensorData;
use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe-sensors';
    protected $description = 'Subscribe to MQTT sensor topics and save data';

    public function handle()
    {
        $server   = env('MQTT_HOST', '127.0.0.1');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'laravel-subscriber';
        $username = env('MQTT_USERNAME', '');
        $password = env('MQTT_PASSWORD', '');

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId);

        try {
            $mqtt->connect($connectionSettings, true);
            $this->info("Connected to MQTT broker $server:$port");
        } catch (\Exception $e) {
            $this->error("Connection failed: " . $e->getMessage());
            return 1;
        }

        $topics = [
            'sensor/dht/temperature',
            'sensor/dht/humidity',
            'sensor/bmp180/temperature',
            'sensor/bmp180/pressure'
        ];

        foreach ($topics as $topic) {
            $mqtt->subscribe($topic, function ($topic, $message) {
                $value = number_format(floatval($message), 2, '.', ''); // pastikan 2 desimal

                $sensor = [];

                switch ($topic) {
                    case 'sensor/dht/temperature':
                        $sensor['temperature'] = $value;
                        break;
                    case 'sensor/dht/humidity':
                        $sensor['humidity'] = $value;
                        break;
                    case 'sensor/bmp180/temperature':
                        $sensor['bmp_temp'] = $value;
                        break;
                    case 'sensor/bmp180/pressure':
                        $sensor['pressure'] = $value;
                        break;
                }

                // update row terakhir (id=1) atau buat baru jika belum ada
                SensorData::updateOrCreate(
                    ['id' => 1],
                    $sensor
                );

                echo "Updated [$topic] => $value\n";
            }, 0);
        }

        $this->info("Subscribed to topics: " . implode(', ', $topics));

        $mqtt->loop(true);
    }
}
