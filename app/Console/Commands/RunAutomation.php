<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Automation;
use App\Models\Relay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class RunAutomation extends Command
{
    protected $signature = 'automation:run';
    protected $description = 'Run scheduled automations';

    public function handle()
    {
        $now = Carbon::now()->format('H:i'); // jam:menit

        $automations = Automation::where('enabled', 1)->get();

        foreach ($automations as $auto) {

            $autoTime = Carbon::parse($auto->time)->format('H:i');

            if ($autoTime === $now) {

                if ($auto->last_run && Carbon::parse($auto->last_run)->format('H:i') === $now) {
                    continue;
                }

                $parts = explode('/', $auto->topic);
                $relayNumber = $parts[1] ?? null;

                if ($relayNumber) {
                    $status = $auto->message == '1' ? 'on' : 'off';

                    Relay::updateOrCreate(
                        ['relay_number' => $relayNumber],
                        ['status' => $status]
                    );
                }

                $this->sendTelegram(
                    "⚡ <b>Automation Triggered</b>\n\n" .
                        "🏷 Name: {$auto->name}\n" .
                        "⏰ Time: {$auto->time}\n" .
                        "📡 Topic: {$auto->topic}\n" .
                        "💬 Message: {$auto->message}"
                );

                $this->sendMQTT($auto->topic, $auto->message);

                $auto->update([
                    'last_run' => now()
                ]);

                $this->info("Executed: {$auto->name}");
            }
        }
    }
    private function sendTelegram($message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error("Telegram failed: " . $e->getMessage());
        }
    }

    private function sendMQTT($topic, $message)
    {
        $server   = env('MQTT_HOST', '127.0.0.1');
        $port     = env('MQTT_PORT', 1883);
        $clientId = 'laravel-publisher-' . uniqid();
        $username = env('MQTT_USERNAME', '');
        $password = env('MQTT_PASSWORD', '');

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId);

        try {
            $mqtt->connect($connectionSettings, true);

            $mqtt->publish($topic, $message, 0);

            $this->info("MQTT sent [$topic] => $message");

            $mqtt->disconnect();
        } catch (\Exception $e) {
            \Log::error("MQTT failed: " . $e->getMessage());
        }
    }
}
