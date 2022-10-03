<?php

declare(strict_types=1);

namespace merms\anno\server\model;

final class MicrotimeDate
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string {
        return $this->value;
    }
}
