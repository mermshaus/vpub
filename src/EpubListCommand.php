<?php

declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class EpubListCommand extends Command
{
    protected static $defaultName = 'epub:list';

    private EpubService $epubService;

    private AnnotationService $annotationService;

    public function __construct(EpubService $epubService, AnnotationService $annotationService)
    {
        parent::__construct();

        $this->epubService       = $epubService;
        $this->annotationService = $annotationService;
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

            $checksum = hash_file('sha256', $filepath);

            $record = $this->annotationService->findRecord($checksum);

            if ($record === null) {
                $output->writeln(sprintf('(no metadata) %s', $filename));

                continue;
            }

            $entries[] = $this->formatEntry($record);
        }

        sort($entries);

        foreach ($entries as $entry) {
            $output->writeln($entry);
        }

        return Command::SUCCESS;
    }

    private function formatEntry(Record $record): string
    {
        $ret = '';

        $data = $record->getData();

        $annotationAuthor = $data->hasValue('epub.author') ? $data->getValue('epub.author') : 'No author';
        $annotationTitle  = $data->hasValue('epub.title') ? $data->getValue('epub.title') : 'No title';

        $ret .= sprintf('%s: %s', $annotationAuthor, $annotationTitle);

        if ($data->hasValue('epub.series')) {
            $annotationSeries = $data->hasValue('epub.series') ? $data->getValue('epub.series') : 'No series';
            $annotationNumber = $data->hasValue('epub.number') ? $data->getValue('epub.number') : 'No number';

            $ret .= sprintf(' (%s, #%s)', $annotationSeries, $annotationNumber);
        }

        return $ret;
    }
}
