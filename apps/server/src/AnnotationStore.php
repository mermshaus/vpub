<?php

declare(strict_types=1);

namespace merms\anno\server;

interface AnnotationStore
{
    public function findRecord(string $checksum): ?Record;

    /**
     * @return Record[]
     */
    public function findAllRecords(): array;

    public function saveRecord(Record $record): void;
}
