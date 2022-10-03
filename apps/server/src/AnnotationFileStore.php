<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;

final class AnnotationFileStore implements AnnotationStore
{
    private string $storageFilepath;

    public function __construct(string $storageFilepath)
    {
        $this->storageFilepath = $storageFilepath;
    }

    public function findRecord(Checksum $checksum): ?Record
    {
        $records = $this->readData();

        foreach ($records as $record) {
            if ($record['checksum'] === $checksum->toString()) {
                return Record::createFromJsonArray($record);
            }
        }

        return null;
    }

    /**
     * @return Record[]
     */
    public function findAllRecords(): array
    {
        $records = $this->readData();

        $ret = [];

        foreach ($records as $record) {
            $ret[] = Record::createFromJsonArray($record);
        }

        return $ret;
    }

    public function saveRecord(Record $record): void
    {
        $data                         = $this->readData();

        $replaceRecord = false;
        $replaceIdx = -1;

        foreach ($data as $idx => $d) {
            $r = Record::createFromJsonArray($d);

            if ($r->getId()->equals($record->getId())) {
                // Replace the existing record.
                $replaceRecord= true;
                $replaceIdx = $idx;
                break;
            }
        }

        if ($replaceRecord === true) {
            $data[$replaceIdx] = $record->toJsonArray();
        }

        if ($replaceRecord === false) {
            // Append new record.
            $data[] = $record->toJsonArray();
        }

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
        file_put_contents($this->storageFilepath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
