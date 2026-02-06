<?php

declare(strict_types=1);

namespace PhpSoftBox\Telegram\Auth;

use function array_key_exists;
use function hash;
use function hash_equals;
use function hash_hmac;
use function implode;
use function is_numeric;
use function ksort;
use function time;

final class TelegramLoginVerifier
{
    public function __construct(
        private readonly string $botToken,
    ) {
    }

    public function verify(array $data, ?int $maxAge = null): ?TelegramLoginUser
    {
        if (!array_key_exists('hash', $data)) {
            return null;
        }

        $hash = (string) $data['hash'];
        unset($data['hash']);

        ksort($data);
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }

        $dataCheckString = implode("\n", $pairs);
        $secretKey = hash('sha256', $this->botToken, true);
        $expectedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

        if (!hash_equals($expectedHash, $hash)) {
            return null;
        }

        if ($maxAge !== null && isset($data['auth_date']) && is_numeric($data['auth_date'])) {
            $authDate = (int) $data['auth_date'];
            if ($authDate + $maxAge < time()) {
                return null;
            }
        }

        if (!isset($data['id']) || !is_numeric($data['id'])) {
            return null;
        }

        return new TelegramLoginUser(
            id: (int) $data['id'],
            firstName: isset($data['first_name']) ? (string) $data['first_name'] : null,
            lastName: isset($data['last_name']) ? (string) $data['last_name'] : null,
            username: isset($data['username']) ? (string) $data['username'] : null,
            photoUrl: isset($data['photo_url']) ? (string) $data['photo_url'] : null,
            authDate: isset($data['auth_date']) && is_numeric($data['auth_date']) ? (int) $data['auth_date'] : null,
            payload: $data,
        );
    }
}
