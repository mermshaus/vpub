<?php

declare(strict_types=1);

namespace merms\anno\server;

final class AnnotationAdd
{
    public function execute(string $sha256sum, string $key, string $value): Record
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $record = $annotationService->findRecord($sha256sum);

        if ($record === null) {
            $record = new Record($sha256sum, new RecordData([$key => [$value]]));
            $annotationService->saveRecord($record);

            return $record;
        }

        $data = $record->getData();

        if ($data->hasValue($key)) {
            $existingValue = $data->getValue($key);

            if (!is_array($existingValue)) {
                $existingValue = [$existingValue];
            }

            $existingValue[] = $value;

            $data->setValue($key, $existingValue);

            $annotationService->saveRecord($record);

            return $record;
        }

        $data->setValue($key, [$value]);
        $annotationService->saveRecord($record);

        return $record;
    }
}
