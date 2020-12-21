<?php

declare(strict_types=1);

namespace merms\vpub;

use merms\anno\apisdk\ApiSdk;
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

            $annotations = $this->apiSdk->getAnnotations($checksum);

            if (count($annotations) === 0) {
                $output->writeln(sprintf('(no metadata) %s', $filename));

                continue;
            }

            $entries[] = $this->formatEntry($annotations);
        }

        sort($entries);

        foreach ($entries as $entry) {
            $output->writeln($entry);
        }

        return Command::SUCCESS;
    }

    private function formatEntry(array $annotations): string
    {
        $ret = '';

        $annotationAuthor = isset($annotations['epub.author']) ? $annotations['epub.author'] : 'No author';
        $annotationTitle  = isset($annotations['epub.title']) ? $annotations['epub.title'] : 'No title';

        $ret .= sprintf('%s: %s', $annotationAuthor, $annotationTitle);

        if (isset($annotations['epub.series'])) {
            $annotationSeries = isset($annotations['epub.series']) ? $annotations['epub.series'] : 'No series';
            $annotationNumber = isset($annotations['epub.number']) ? $annotations['epub.number'] : 'No number';

            $ret .= sprintf(' (%s, #%s)', $annotationSeries, $annotationNumber);
        }

        return $ret;
    }
}
