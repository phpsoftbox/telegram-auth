# Telegram Auth

## About

`phpsoftbox/telegram-auth` — адаптер авторизации через Telegram Login Widget для экосистемы PhpSoftBox.

## Quick Start

```php
use PhpSoftBox\Telegram\Auth\TelegramLoginVerifier;

$verifier = new TelegramLoginVerifier($_ENV['TELEGRAM_BOT_TOKEN']);
$user = $verifier->verify($payload, maxAge: 86400);

if ($user === null) {
    // Неверная подпись или истек срок.
}
```

## Документация

- [Использование](docs/01-usage.md)
- [Интеграция с Auth](docs/02-auth-integration.md)
