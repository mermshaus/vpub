<?php

declare(strict_types=1);

namespace merms\vpub;

final class Record
{
    private string $checksum;

    private RecordData $data;

    public function __construct(string $checksum, RecordData $data)
    {
        $this->checksum = $checksum;
        $this->data     = $data;
    }

    public static function createFromJsonArray(string $checksum, array $json): self
    {
        $data = new RecordData($json['data']);

        return new self($checksum, $data);
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function getData(): RecordData
    {
        return $this->data;
    }

    public function toJsonArray(): array
    {
        return ['data' => $this->data->toJsonArray()];
    }
}
