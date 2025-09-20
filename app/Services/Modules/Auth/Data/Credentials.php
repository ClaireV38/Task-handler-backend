<?php

namespace App\Services\Modules\Auth\Data;

final class Credentials
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $ip = null
    ) {
    }

    /**
     * @param array{email: string, password: string, ip: string|null} $data
     * @return self
     */
    public static function from(array $data): self
    {
        return new self($data['email'], $data['password'], $data['ip'] ?? null);
    }
}
