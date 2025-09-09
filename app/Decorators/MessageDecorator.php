<?php

namespace App\Decorators;

abstract class MessageDecorator implements MessageService
{
    protected MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    abstract public function send(string $message): string;
}
