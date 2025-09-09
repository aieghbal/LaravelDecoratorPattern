# الگوی طراحی به زبان ساده - Decorator در لاراول

## 📌 مقدمه
الگوی **Decorator** به ما اجازه می‌دهد بدون تغییر در کلاس اصلی، قابلیت‌های جدیدی به آن اضافه کنیم.  
این الگو به‌خصوص وقتی مفید است که می‌خواهیم **رفتارهای مختلفی** را به‌صورت **پویا** و **قابل ترکیب** به یک شیء اضافه کنیم.

در این مثال یک سرویس پیام ساده داریم و با استفاده از **Decorator** قابلیت‌های زیر را به آن اضافه می‌کنیم:
- ثبت لاگ پیام
- رمزنگاری پیام
- اضافه کردن امضای مدیریت

---

## 🛠 مراحل پیاده‌سازی

### 1. تعریف اینترفیس پایه
```php
namespace App\Decorators;

interface MessageService
{
    public function send(string $message): string;
}
```

### 2. کلاس اصلی (کامپوننت پایه)
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

###  3. کلاس Decorator انتزاعی
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

###  4. پیاده‌سازی Decoratorها
#### لاگ‌کردن پیام
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

###  رمزنگاری پیام
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

###   اضافه کردن امضا
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

###   5. کنترلر برای تستا
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

        // مرحله ۱: لاگ کردن
        $messageService = new LogDecorator($messageService);

        // مرحله ۲: رمزنگاری
        $messageService = new EncryptDecorator($messageService);

        // مرحله ۳: اضافه کردن امضا
        $messageService = new SignatureDecorator($messageService);

        $finalMessage = $messageService->send("سلام کاربر عزیز!");

        return response($finalMessage);
    }
}
```

###   6. تعریف Routeا
#### فایل: routes/web.php
```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecoratorController;

Route::get('/decorator', [DecoratorController::class, 'index']);
```

### ▶️ تست نهایی


#### اجرای سرور:
```php
php artisan serve
```
#### مرورگر:
```php
http://127.0.0.1:8000/decorator
```
####  خروجی:
```php
پیام اصلی: U2FsYW0g2KrYp9mG2K8g2KrYp9ix2YbZhtuM!
-- امضا: مدیریت سایت
```
####  در فایل لاگ لاراول (storage/logs/laravel.log) ثبت می‌شود:

```php
[INFO] ارسال پیام: سلام کاربر !عزیز
```


### 🎯 نتیجه‌گیری
* کلاس اصلی (BasicMessageService) بدون تغییر باقی ماند.
* رفتارهای جدید را با استفاده از Decoratorها به‌صورت ماژولار اضافه کردیم.
* این قابلیت‌ها قابل ترکیب هستند (مثلاً فقط لاگ + امضا یا فقط رمزنگاری).


الگوی Decorator یک روش عالی برای افزودن ویژگی‌های جانبی به کلاس‌ها بدون تغییر کد اصلی است.
