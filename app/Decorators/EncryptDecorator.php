<?php

namespace App\Decorators;

class EncryptDecorator extends MessageDecorator
{
    public function send(string $message): string
    {
        $encrypted = base64_encode($message); // برای سادگی از base64 استفاده کردیم
        return $this->service->send($encrypted);
    }
}
