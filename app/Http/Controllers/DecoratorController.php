<?php

namespace App\Http\Controllers;

use App\Decorators\BasicMessageService;
use App\Decorators\EncryptDecorator;
use App\Decorators\LogDecorator;
use App\Decorators\SignatureDecorator;

class DecoratorController extends Controller
{
    public function index()
    {
        $messageService = new BasicMessageService();

        // مرحله ۱: لاگ کردن
        $messageService = new LogDecorator($messageService);

        // مرحله ۲: رمزنگاری
        $messageService = new EncryptDecorator($messageService);

        // مرحله ۳: امضا اضافه شود
        $messageService = new SignatureDecorator($messageService);

        $finalMessage = $messageService->send("سلام کاربر عزیز!");

        return response($finalMessage);
    }
}

