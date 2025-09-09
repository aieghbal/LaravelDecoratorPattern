<?php

namespace App\Decorators;

class SignatureDecorator extends MessageDecorator
{
    public function send(string $message): string
    {
        $result = $this->service->send($message);
        return $result . "\n-- امضا: مدیریت سایت";
    }
}
