<?php

declare(strict_types=1);

namespace PhpSoftBox\Telegram\Auth\Tests;

use PhpSoftBox\Telegram\Auth\TelegramLoginVerifier;
use PHPUnit\Framework\TestCase;

use function hash;
use function hash_hmac;
use function implode;
use function ksort;
use function time;

final class TelegramLoginVerifierTest extends TestCase
{
    /**
     * Проверяем успешную верификацию payload.
     */
    public function testVerifySuccess(): void
    {
        $token = 'secret-token';
        $payload = [
            'id' => 123,
            'first_name' => 'John',
            'auth_date' => time(),
        ];

        $payload['hash'] = $this->makeHash($token, $payload);

        $verifier = new TelegramLoginVerifier($token);
        $user = $verifier->verify($payload, maxAge: 3600);

        $this->assertNotNull($user);
        $this->assertSame(123, $user->id());
    }

    /**
     * Проверяем отказ при неверной подписи.
     */
    public function testVerifyFail(): void
    {
        $verifier = new TelegramLoginVerifier('token');

        $payload = [
            'id' => 1,
            'first_name' => 'John',
            'auth_date' => time(),
            'hash' => 'bad',
        ];

        $this->assertNull($verifier->verify($payload));
    }

    private function makeHash(string $token, array $payload): string
    {
        $data = $payload;
        unset($data['hash']);

        ksort($data);
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }

        $dataCheckString = implode("\n", $pairs);
        $secretKey = hash('sha256', $token, true);

        return hash_hmac('sha256', $dataCheckString, $secretKey);
    }
}
