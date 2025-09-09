<?php

namespace App\Decorators;

interface MessageService
{
    public function send(string $message): string;
}
