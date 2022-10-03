<?php

declare(strict_types=1);

namespace merms\vpub;

use merms\anno\apisdk\ApiSdk;
use merms\anno\checksum_cache\CacheService;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EpubListCommand extends Command
{
    protected static $defaultName = 'epub:list';

    private EpubService $epubService;

    private ApiSdk $apiSdk;

    private CacheService $cacheService;

    public function __construct(EpubService $epubService, ApiSdk $apiSdk, CacheService $cacheService)
    {
        parent::__construct();

        $this->epubService  = $epubService;
        $this->apiSdk       = $apiSdk;
        $this->cacheService = $cacheService;
    }

    protected function configure()
    {
        $this->addArgument('directory', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argDirectory = $input->getArgument('directory');

        $epubFiles = $this->epubService->findEpubFilesInDirectory($argDirectory);

        $entries = [];

        foreach ($epubFiles as $epubFile) {
            /** @var \SplFileInfo $epubFile */
            $filepath = $epubFile->getPathname();
            $filename = $epubFile->getFilename();

            $checksum = $this->cacheService->getSha256Sum($filepath);

            $record = $this->apiSdk->getRecord($checksum);

            if ($record === null) {
                $output->writeln(sprintf('(no metadata) %s', $filename));

                continue;
            }

            $entries[] = $this->formatEntry($record['data'], $filepath);
        }

        usort($entries, function ($a, $b) {
            return strcasecmp($a['title'], $b['title']);
        });


        $n = 1;
        foreach ($entries as $entry) {
            $output->writeln($n . ') ' . $entry['title']);
            $output->writeln(str_repeat(' ', 8) . 'by ' . $entry['author']);
            //$output->writeln(str_repeat(' ', 8) . $entry['filepath']);
            $output->writeln('');
            $n++;
        }

        return Command::SUCCESS;
    }

    private function formatEntry(array $annotations, string $filepath): array
    {
        $annotationAuthor = 'No author';

        $values = $this->getValuesByKey($annotations, 'epub.author');

        if (count($values)>0) {
            $annotationAuthor = implode(', ', $values);
        }

        $annotationTitle  = 'No title';

        $values = $this->getValuesByKey($annotations, 'epub.title');

        if (count($values)===1) {
            $annotationTitle = $values[0];
        } elseif (count($values) > 1) {
            throw new RuntimeException('More than one title found');
        }

        return ['author' => $annotationAuthor, 'title'=>$annotationTitle, 'filepath' => $filepath];
    }

    private function getValuesByKey(array $annotations, string $key): array {
        $values = [];

        foreach ($annotations as $annotation) {
            if ($annotation['key'] !== $key) {
                continue;
            }

            $values[] = $annotation['value'];
        }

        return $values;
    }
}
