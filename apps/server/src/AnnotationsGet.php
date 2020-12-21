<?php

declare(strict_types=1);

namespace merms\anno\server;

final class AnnotationsGet
{
    public function execute(string $sha256sum): array
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $record = $annotationService->findRecord($sha256sum);

        if ($record === null) {
            return [];
        }

        return $record->getData()->toJsonArray();
    }
}
