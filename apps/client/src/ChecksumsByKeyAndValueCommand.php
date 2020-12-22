<?php

declare(strict_types=1);

namespace merms\anno\client;

use merms\anno\apisdk\ApiSdk;
use merms\anno\checksum_cache\CacheService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ChecksumsByKeyAndValueCommand extends Command
{
    protected static $defaultName = 'checksums:get-by-key-and-value';

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
        $this->addArgument('key', InputArgument::REQUIRED);
        $this->addArgument('value', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argKey   = $input->getArgument('key');
        $argValue = $input->getArgument('value');

        $checksums = $this->apiSdk->getChecksumsByKeyAndValue($argKey, $argValue);

        $checksums = array_keys($checksums);

        foreach ($checksums as $checksum) {
            $filepathCandidate = $this->cacheService->findFilepath($checksum);

            if ($filepathCandidate === null) {
                continue;
            }

            $filepath = $filepathCandidate;

            $output->writeln($filepath);
        }

        return Command::SUCCESS;
    }
}
