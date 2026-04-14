<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorData;
use Illuminate\Support\Facades\Http;

class CheckTemperature extends Command
{
    protected $signature = 'sensor:check-temperature';
    protected $description = 'Check temperature and send Telegram alert if above threshold';

    public function handle()
    {
        $threshold = 10;

        $sensor = SensorData::find(1);

        if (!$sensor || !$sensor->temperature) {
            $this->error('No temperature data found');
            return 1;
        }

        $temperature = floatval($sensor->temperature);

        $this->info("Current temperature: {$temperature}°C");

        if ($temperature > $threshold) {

            $message = "WARNING!\nSuhu ruangan terdeteksi tinggi!\n\n🌡️ Suhu: {$temperature}°C\nBatas: {$threshold}°C";

            $this->sendTelegram($message);

            $this->warn("Alert sent to Telegram!");
        } else {
            $this->info("Temperature is normal.");
        }

        return 0;
    }

    private function sendTelegram($message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }
}
