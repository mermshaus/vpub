<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;

final class AnnotationAdd
{
    public function execute(Checksum $sha256sum, string $key, string $value): Record
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $record = $annotationService->findRecord($sha256sum);

        $createdAt = TimeService::getMicrotime();
        $modifiedAt = $createdAt;

        $newRecordDataEntry = new RecordDataEntry(IdService::generate(),$createdAt, $modifiedAt,$key, $value);

        if ($record === null) {
            $uuid = IdService::generate();
            $entries = [$newRecordDataEntry];

            $newRecord = new Record($uuid, $createdAt, $sha256sum, new RecordData($entries));
        } else {
            $newRecord = $record->withAdditionalEntry($newRecordDataEntry);
        }

        $annotationService->saveRecord($newRecord);

        return $newRecord;
    }
}
