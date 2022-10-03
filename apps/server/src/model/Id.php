<?php

declare(strict_types=1);

namespace merms\anno\server\model;

final class Id
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string {
        return $this->value;
    }

    public function equals(Id $candidate): bool
    {
        return $this->value === $candidate->value;
    }
}
