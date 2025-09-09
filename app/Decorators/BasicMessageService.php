<?php

namespace App\Decorators;

class BasicMessageService implements MessageService
{
    public function send(string $message): string
    {
        return "پیام اصلی: " . $message;
    }
}
