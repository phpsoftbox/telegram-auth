# Использование

`TelegramLoginVerifier` проверяет подпись payload, отправленного Telegram Login Widget. Метод `verify()` вернет пользователя или `null`, если подпись не совпадает или истек срок действия.

```php
use PhpSoftBox\Telegram\Auth\TelegramLoginVerifier;

$verifier = new TelegramLoginVerifier($_ENV['TELEGRAM_BOT_TOKEN']);
$user = $verifier->verify($payload, maxAge: 86400);
```
