<?php

declare(strict_types=1);

namespace merms\anno\server;

final class ChecksumsGetByKeyAndValue
{
    public function execute(string $key, string $value): array
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $records = $annotationService->findAllRecords();

        $recordsFiltered = array_filter($records, function (Record $record) use ($key, $value) {
            foreach ($record->getData()->getEntriesWithKey($key) as $entry) {

                if ($value==='*') {
                    return true;
                }

                if ($value===$entry->getValue()) {
                    return true;
                }
            }

            return false;
        });

        $arr = [];

        foreach ($recordsFiltered as $record) {
            $arr[$record->getChecksum()->toString()] = $record->toJsonArray();
        }

        return $arr;
    }
}
