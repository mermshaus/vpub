<?php

declare(strict_types=1);

namespace merms\anno\server;

final class RecordGet
{
    public function execute(string $sha256sum): ?Record
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        return $annotationService->findRecord($sha256sum);
    }
}
