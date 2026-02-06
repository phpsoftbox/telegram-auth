# Интеграция с Auth

`phpsoftbox/telegram-auth` можно подключить к компоненту `Auth` через `CallbackGuard`.

## Пример

```php
use PhpSoftBox\Auth\Guard\CallbackGuard;
use PhpSoftBox\Auth\Manager\AuthManager;
use PhpSoftBox\Auth\Middleware\AuthMiddleware;
use PhpSoftBox\Telegram\Auth\TelegramLoginVerifier;
use Psr\Http\Message\ServerRequestInterface;

$verifier = new TelegramLoginVerifier($_ENV['TELEGRAM_BOT_TOKEN']);

$telegramGuard = new CallbackGuard(static function (ServerRequestInterface $request) use ($verifier, $users) {
    $payload = $request->getParsedBody();
    if (!is_array($payload)) {
        $payload = [];
    }

    $login = $verifier->verify($payload, maxAge: 86400);
    if ($login === null) {
        return null;
    }

    return $users->findByTelegramId($login->id());
});

$auth = new AuthManager([
    'telegram' => $telegramGuard,
], defaultGuard: 'telegram');

$middleware = new AuthMiddleware($auth, guardName: 'telegram');
```

Рекомендуется использовать `AuthMiddleware` на маршрутах, где требуется Telegram-аутентификация.
