<?php

declare(strict_types=1);

namespace App\Domain\Shared;

class UUID
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function create(): self
    {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UUID $uuid): bool
    {
        return $this->value() === $uuid->value();
    }
}
