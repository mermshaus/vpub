<?php

declare(strict_types=1);

namespace merms\anno\checksum_cache;

final class CacheService
{
    private string $cacheFilepath;

    public function __construct(string $cacheFilepath)
    {
        $this->cacheFilepath = $cacheFilepath;

        if (!file_exists($cacheFilepath)) {
            file_put_contents($cacheFilepath, json_encode([]));
        }
    }

    public function findFilepath(string $checksum): ?string
    {
        $cacheEntries = json_decode(file_get_contents($this->cacheFilepath), true);

        foreach ($cacheEntries as $filepath => $cacheEntryData) {
            if ($cacheEntryData['sha256sum'] === $checksum) {
                return $filepath;
            }
        }

        return null;
    }

    public function getSha256Sum(string $filepath): string
    {
        $cacheEntries = json_decode(file_get_contents($this->cacheFilepath), true);

        $save = false;

        if (!isset($cacheEntries[$filepath])) {
            $filesize  = filesize($filepath);
            $sha256sum = hash_file('sha256', $filepath);

            $cacheEntries[$filepath] = [
                'filesize'  => $filesize,
                'sha256sum' => $sha256sum,
            ];

            $save = true;
        }

        if ($save === true) {
            file_put_contents($this->cacheFilepath, json_encode($cacheEntries));
        }

        return $cacheEntries[$filepath]['sha256sum'];
    }
}
