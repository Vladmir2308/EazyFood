<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function createChat(Request $request)
    {
        $data = $request->all();

        Log::info('Webhook IN:', $request->all());

        if (isset($data['message']['text']) && str_starts_with($data['message']['text'], '/start')) {
            $parts = explode(' ', $data['message']['text']);
            if (isset($parts[1])) {
                $userId = $parts[1];
                $chatId = $data['message']['chat']['id'];

                User::where('id', $userId)->update(['telegram_chat_id' => $chatId]);

                Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [
                    'chat_id' => $chatId,
                    'text'    => "✅ Теперь я буду присылать тебе корзину прямо сюда!"
                ]);
            }
        }
    }

    public function sendBasketMessage(Request $request)
    {
        $data = $request['products'];

        $message = "";

        foreach ($data as $category => $items) {
            $message .= "📌 *{$category}:*\n";
            foreach ($items as $item) {
                $message .= " - {$item['name']} {$item['total']} {$item['unit']}\n";
            }
            $message .= "\n";
        }

        Http::post("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendMessage", [
            'chat_id' => auth()->user()->telegram_chat_id,
            'text'    => $message,
            'parse_mode' => 'Markdown', // чтобы категории были жирным
        ]);
    }
}
