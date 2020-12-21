<?php

declare(strict_types=1);

namespace merms\anno\client;

use merms\anno\apisdk\ApiSdk;
use merms\anno\checksum_cache\CacheService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnnotationGetCommand extends Command
{
    protected static $defaultName = 'annotation:get';

    private ApiSdk $apiSdk;

    private CacheService $cacheService;

    public function __construct(ApiSdk $apiSdk, CacheService $cacheService)
    {
        parent::__construct();

        $this->apiSdk       = $apiSdk;
        $this->cacheService = $cacheService;
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

        $filepath = realpath($argFilepath);

        if ($filepath === false) {
            throw new \RuntimeException(sprintf('Invalid filepath: "%s"', $filepath));
        }

        $checksum = $this->cacheService->getSha256Sum($filepath);

        $data = $this->apiSdk->getAnnotations($checksum);

        if (!isset($data[$argKey])) {
            throw new \RuntimeException('Key not found');
        }

        $value = $data[$argKey];

        $output->writeln($value);

        return Command::SUCCESS;
    }
}
