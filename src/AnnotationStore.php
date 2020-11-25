<?php

declare(strict_types=1);

namespace merms\vpub;

interface AnnotationStore
{
    public function findRecord(string $checksum): ?Record;

    public function saveRecord(Record $record): void;
}
