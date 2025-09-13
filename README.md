# Design Pattern Made Simple – Decorator in Laravel

## 📌 Introduction

The Decorator pattern allows us to add new functionalities to a class without modifying the original class.
This pattern is especially useful when we want to add different behaviors to an object dynamically and in a composable way.

In this example, we have a simple messaging service, and using the Decorator, we add the following capabilities:

Logging messages

Encrypting messages

Adding an admin signature

---

## 🛠 Implementation Steps

### 1. Define the Base Interface
```php
namespace App\Decorators;

interface MessageService
{
    public function send(string $message): string;
}
```

### 2. Main Class (Base Component)
```php
namespace App\Decorators;

class BasicMessageService implements MessageService
{
    public function send(string $message): string
    {
        return "پیام اصلی: " . $message;
    }
}
```

###  3. Abstract Decorator Class
```php
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
```

###  4. Implementing Decorators
#### Logging Messages
```php
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
```

###  Encrypting Messages
```php
namespace App\Decorators;

class EncryptDecorator extends MessageDecorator
{
    public function send(string $message): string
    {
        $encrypted = base64_encode($message);
        return $this->service->send($encrypted);
    }
}
```

###  Adding a Signature
```php
namespace App\Decorators;

class SignatureDecorator extends MessageDecorator
{
    public function send(string $message): string
    {
        $result = $this->service->send($message);
        return $result . "\n-- امضا: مدیریت سایت";
    }
}
```

###   5. Controller for Testing
```php
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

        // Step 1: Logging
        $messageService = new LogDecorator($messageService);

        // Step 2: Encryption
        $messageService = new EncryptDecorator($messageService);

       // Step 3: Adding Signature
        $messageService = new SignatureDecorator($messageService);

        $finalMessage = $messageService->send("سلام کاربر عزیز!");

        return response($finalMessage);
    }
}
```

###   6. Define Routes
#### File: routes/web.php
```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecoratorController;

Route::get('/decorator', [DecoratorController::class, 'index']);
```

### ▶️ Final Test


#### Start Laravel Server:
```php
php artisan serve
```
#### Browser:
```php
http://127.0.0.1:8000/decorator
```
####  Output:
```php
Original Message: U2FsYW0g2KrYp9mG2K8g2KrYp9ix2YbZhtuM!
-- Signature: Admin
```
####  Laravel Log File (storage/logs/laravel.log) Records:

```php
[INFO] Sending message: Hello dear user!
```


### 🎯 Conclusion
* The main class (BasicMessageService) remained unchanged.
* New behaviors were added modularly using Decorators.
* These behaviors are composable (e.g., log + signature only, or encryption only).


The Decorator pattern is an excellent way to add additional features to classes without modifying the original code.

---
🌐 [Persian version](./README.fa.md)
