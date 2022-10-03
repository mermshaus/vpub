<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;

final class RecordGet
{
    public function execute(Checksum $sha256sum): ?Record
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        return $annotationService->findRecord($sha256sum);
    }
}
