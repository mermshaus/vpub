<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Id;

final class RecordData
{
    /**
     * @var array<RecordDataEntry>
     */
    private array $recordDataEntries;

    /**
     * @param array<RecordDataEntry> $recordDataEntries
     */
    public function __construct(array $recordDataEntries)
    {
        $this->recordDataEntries = $recordDataEntries;
    }

    /**
     * @return array<RecordDataEntry>
     */
    public function getAll(): array
    {
        return $this->recordDataEntries;
    }

    /**
     * @return array<RecordDataEntry>
     */
    public function getEntriesWithKey(string $key): array {
        $findings = [];

        foreach ($this->recordDataEntries as $recordDataEntry) {
            if ($recordDataEntry->getKey()===$key) {
                $findings[] = $recordDataEntry;
            }
        }

        return $findings;
    }

    public function getEntryWithId(Id $id): RecordDataEntry {
        foreach ($this->recordDataEntries as $recordDataEntry) {
            if ($recordDataEntry->getId()->equals($id)) {
                return $recordDataEntry;
            }
        }

        throw new \RuntimeException(sprintf('Entry with id %s does not exist.', $id->toString()));
    }

    public function hasEntryWithId(Id $id): bool {
        foreach ($this->recordDataEntries as $recordDataEntry) {
            if ($recordDataEntry->getId()->equals($id)) {
                return true;
            }
        }

        return false;
    }

    public function toJsonArray(): array
    {
        $array = [];

        foreach ($this->recordDataEntries as $recordDataEntry) {
            $array[] = [
                'id'=>$recordDataEntry->getId()->toString(),
                'created_at'=> $recordDataEntry->getCreatedAt()->toString(),
                'modified_at'=> $recordDataEntry->getModifiedAt()->toString(),
                'key' => $recordDataEntry->getKey(),
                'value' => $recordDataEntry->getValue(),
            ];
        }

        return $array;
    }

    public static function createFromJsonArray(array $json): self {
        $entries = [];

        foreach ($json as $entry) {
            $entries[] = RecordDataEntry::createFromJsonArray($entry);
        }

        return new RecordData($entries);
    }
}
