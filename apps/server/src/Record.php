<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;
use merms\anno\server\model\Id;
use merms\anno\server\model\MicrotimeDate;

final class Record
{
    private Id $id;

    private MicrotimeDate $createdAt;

    private Checksum $checksum;

    private RecordData $data;

    public function __construct(Id $id, MicrotimeDate $createdAt, Checksum $checksum, RecordData $data)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->checksum = $checksum;
        $this->data     = $data;
    }

    public static function createFromJsonArray(array $json): self
    {
        $id = new Id($json['id']);
        $createdAt = new MicrotimeDate($json['created_at']);
        $checksum = new Checksum($json['checksum']);
        $recordData = RecordData::createFromJsonArray($json['data']);

        return new self($id, $createdAt, $checksum, $recordData);
    }

    public function withAdditionalEntry(RecordDataEntry  $newEntry): self {
        $entries = $this->getData()->getAll();
        $entries[] = $newEntry;
        return new self($this->id, $this->createdAt, $this->checksum, new RecordData($entries));
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getChecksum(): Checksum
    {
        return $this->checksum;
    }

    public function getData(): RecordData
    {
        return $this->data;
    }

    public function toJsonArray(): array
    {
        return ['id' => $this->id->toString(), 'created_at' => $this->createdAt->toString(), 'checksum' => $this->checksum->toString(), 'data' => $this->data->toJsonArray()];
    }
}
