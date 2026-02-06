<?php

declare(strict_types=1);

namespace PhpSoftBox\Telegram\Auth;

final class TelegramLoginUser
{
    public function __construct(
        private readonly int $id,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
        private readonly ?string $username = null,
        private readonly ?string $photoUrl = null,
        private readonly ?int $authDate = null,
        private readonly array $payload = [],
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function firstName(): ?string
    {
        return $this->firstName;
    }

    public function lastName(): ?string
    {
        return $this->lastName;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function photoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function authDate(): ?int
    {
        return $this->authDate;
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
