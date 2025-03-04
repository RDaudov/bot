<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    private $botToken;

    public function __construct()
    {
        $this->botToken = env('TG_BOT_TOKEN');
    }

    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['message'])) {
            $chatId = $data['message']['chat']['id'];
            $text = $data['message']['text'];

            if ($text === '/start') {
                $this->sendMessage($chatId, 'Привет! Я ваш бот. Используйте /help для списка команд.');
            } elseif ($text === '/help') {
                $this->sendMessage($chatId, 'Доступные команды: /start, /help');
            } else {
                $this->sendMessage($chatId, 'Я не понимаю эту команду.');
            }
        }

        return response('OK', 200);
    }

    private function sendMessage($chatId, $text)
    {
        $url = "https://api.telegram.org/bot" . $this->botToken . "/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        Http::post($url, $data);
    }
}
