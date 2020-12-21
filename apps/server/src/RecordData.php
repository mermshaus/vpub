<?php

declare(strict_types=1);

namespace merms\anno\server;

final class RecordData
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function deleteValue(string $key): void
    {
        if (!$this->hasValue($key)) {
            throw new \RuntimeException();
        }

        unset($this->data[$key]);
    }

    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getValue(string $key)
    {
        if (!$this->hasValue($key)) {
            throw new \RuntimeException();
        }

        return $this->data[$key];
    }

    public function hasValue(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @param mixed $value
     */
    public function setValue(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function toJsonArray(): array
    {
        return $this->data;
    }
}
