<?php

declare(strict_types=1);

namespace merms\vpub;

use merms\anno\apisdk\ApiSdk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AnnotationRemoveCommand extends Command
{
    protected static $defaultName = 'annotation:remove';

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

        $checksum = $this->cacheService->getSha256Sum($argFilepath);

        $annotations = $this->apiSdk->getAnnotations($checksum);

        unset($annotations[$argKey]);

        $this->apiSdk->setAnnotations($checksum, $annotations);

        return Command::SUCCESS;
    }
}
