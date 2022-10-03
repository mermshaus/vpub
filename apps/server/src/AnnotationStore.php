<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;

interface AnnotationStore
{
    public function findRecord(Checksum $checksum): ?Record;

    /**
     * @return Record[]
     */
    public function findAllRecords(): array;

    public function saveRecord(Record $record): void;
}
