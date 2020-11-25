<?php

declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnnotationGetCommand extends Command
{
    protected static $defaultName = 'annotation:get';

    private AnnotationService $annotationService;

    public function __construct(AnnotationService $annotationService)
    {
        parent::__construct();

        $this->annotationService = $annotationService;
    }

    protected function configure()
    {
        $this->addArgument('filepath', InputArgument::REQUIRED);
        $this->addArgument('key', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argFilepath = $input->getArgument('filepath');
        $argKey      = $input->getArgument('key');

        $checksum = hash_file('sha256', $argFilepath);

        $record = $this->annotationService->findRecord($checksum);

        if ($record === null) {
            throw new \RuntimeException('Record not found');
        }

        $value = $record->getData()->getValue($argKey);

        $output->writeln($value);

        return Command::SUCCESS;
    }
}
