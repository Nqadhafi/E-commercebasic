<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected string $token;
    protected ?string $sender;
    protected string $endpoint = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $s = StoreSetting::first();
        $this->token  = $s->fonnte_token ?? env('FONNTE_TOKEN', '');
        $this->sender = $s->fonnte_sender ?? env('FONNTE_SENDER');
    }

    public function sendText(string $to, string $message, array $meta = []): bool
    {
        $body = array_filter([
            'target' => $to,
            'message'=> $message,
            'countryCode' => '62',
            'sender' => $this->sender, // opsional
        ]);

        $res = Http::withHeaders(['Authorization'=>$this->token])
            ->acceptJson()
            ->timeout(20)
            ->asForm()
            ->post($this->endpoint, $body);

        NotificationLog::create([
            'channel' => 'whatsapp',
            'event' => $meta['event'] ?? null,
            'target' => $to,
            'payload'=> $body,
            'status_code' => $res->status(),
            'success' => $res->successful(),
            'response_body' => $res->body(),
            'order_id' => $meta['order_id'] ?? null,
        ]);

        return $res->successful();
    }
}
