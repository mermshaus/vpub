<?php

declare(strict_types=1);

namespace merms\anno\server;

final class AnnotationsSet
{
    public function execute(string $sha256sum, array $annotations): void
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $record = new Record($sha256sum, new RecordData($annotations));

        $annotationService->saveRecord($record);
    }
}
