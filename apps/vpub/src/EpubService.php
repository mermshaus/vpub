<?php

declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Finder\Finder;

final class EpubService
{
    public function findEpubFilesInDirectory(string $directory): array
    {
        $finder = new Finder();

        $finder->name(['*.epub', '*.pdf']);

        return iterator_to_array($finder->in($directory)->getIterator());
    }
}
