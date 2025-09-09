<?php

namespace App\Decorators;

use Illuminate\Support\Facades\Log;

class LogDecorator extends MessageDecorator
{
    public function send(string $message): string
    {
        Log::info("ارسال پیام: " . $message);
        return $this->service->send($message);
    }
}
