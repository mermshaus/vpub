<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Id;
use merms\anno\server\model\MicrotimeDate;

final class RecordDataEntry
{
    private Id $id;
    private MicrotimeDate $createdAt;
    private MicrotimeDate $modifiedAt;
    private string $key;
    private string $value;

    public function __construct(Id $id, MicrotimeDate $createdAt, MicrotimeDate $modifiedAt, string $key, string $value)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
        $this->key = $key;
        $this->value = $value;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getCreatedAt(): MicrotimeDate
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): MicrotimeDate
    {
        return $this->modifiedAt;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function createFromJsonArray(array $json): self
    {
        return new RecordDataEntry(
            new Id($json['id']),
            new MicrotimeDate($json['created_at']),
            new MicrotimeDate($json['modified_at']),
            $json['key'],
            $json['value']
        );
    }
}
