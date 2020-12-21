<?php

declare(strict_types=1);

namespace merms\anno\server;

final class AnnotationFileStore implements AnnotationStore
{
    private string $storageFilepath;

    public function __construct(string $storageFilepath)
    {
        $this->storageFilepath = $storageFilepath;
    }

    public function findRecord(string $checksum): ?Record
    {
        $records = $this->readData();

        if (!isset($records[$checksum])) {
            return null;
        }

        return Record::createFromJsonArray($checksum, $records[$checksum]);
    }

    /**
     * @return Record[]
     */
    public function findAllRecords(): array
    {
        $records = $this->readData();

        $ret = [];

        foreach ($records as $key => $data) {
            $ret[] = Record::createFromJsonArray($key, $data);
        }

        return $ret;
    }

    public function saveRecord(Record $record): void
    {
        $data                         = $this->readData();
        $data[$record->getChecksum()] = $record->toJsonArray();
        $this->writeData($data);
    }

    private function readData(): array
    {
        if (!file_exists($this->storageFilepath)) {
            return [];
        }

        return json_decode(file_get_contents($this->storageFilepath), true);
    }

    private function writeData(array $data): void
    {
        file_put_contents($this->storageFilepath, json_encode($data));
    }
}
