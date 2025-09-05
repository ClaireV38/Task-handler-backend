<?php
namespace App\Domain\Auth\Data;

final class Credentials
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $ip = null
    ) {}

    public static function from(array $data): self
    {
        return new self($data['email'], $data['password'], $data['ip'] ?? null);
    }
}
