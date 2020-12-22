<?php

declare(strict_types=1);

namespace merms\anno\server;

final class ChecksumsGetByKeyAndValue
{
    /**
     * @param mixed $value
     */
    public function execute(string $key, $value): array
    {
        $annotationService = new AnnotationService(new AnnotationFileStore('/home/marc/.local/share/vpub/data.json'));

        $records = $annotationService->findAllRecords();

        $recordsFiltered = array_filter($records, function (Record $record) use ($key, $value) {
            if ($record->getData()->hasValue($key)) {
                $entry = $record->getData()->getValue($key);

                if (!is_array($entry)) {
                    return $record->getData()->getValue($key) === $value;
                }

                foreach ($entry as $entryValue) {
                    if ($entryValue === $value) {
                        return true;
                    }
                }
            }

            return false;
        });

        $arr = [];

        foreach ($recordsFiltered as $record) {
            $arr[$record->getChecksum()] = $record->toJsonArray();
        }

        return $arr;
    }
}
