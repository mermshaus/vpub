<?php

declare(strict_types=1);

namespace merms\anno\server;

final class AnnotationService
{
    private AnnotationStore $annotationStore;

    public function __construct(AnnotationStore $annotationStore)
    {
        $this->annotationStore = $annotationStore;
    }

    public function findRecord(string $checksum): ?Record
    {
        return $this->annotationStore->findRecord($checksum);
    }

    /**
     * @return Record[]
     */
    public function findAllRecords(): array
    {
        return $this->annotationStore->findAllRecords();
    }

    public function saveRecord(Record $record): void
    {
        $this->annotationStore->saveRecord($record);
    }
}
