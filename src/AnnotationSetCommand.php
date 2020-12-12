<?php

declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnnotationSetCommand extends Command
{
    protected static $defaultName = 'annotation:set';

    private AnnotationService $annotationService;

    private CacheService $cacheService;

    public function __construct(AnnotationService $annotationService, CacheService $cacheService)
    {
        parent::__construct();

        $this->annotationService = $annotationService;
        $this->cacheService      = $cacheService;
    }

    protected function configure()
    {
        $this->addArgument('filepath', InputArgument::REQUIRED);
        $this->addArgument('key', InputArgument::REQUIRED);
        $this->addArgument('value', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argFilepath = $input->getArgument('filepath');
        $argKey      = $input->getArgument('key');
        $argValue    = $input->getArgument('value');

        $filepath = realpath($argFilepath);

        if ($filepath === false) {
            throw new \RuntimeException(sprintf('Invalid filepath: "%s"', $filepath));
        }

        $checksum = $this->cacheService->getSha256Sum($filepath);

        $record = $this->annotationService->findRecord($checksum);

        if ($record === null) {
            $record = new Record($checksum, new RecordData([]));
        }

        $record->getData()->setValue($argKey, $argValue);

        $this->annotationService->saveRecord($record);

        return Command::SUCCESS;
    }
}
