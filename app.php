#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$storageDir = getenv('HOME') . '/.local/share/vpub';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

$annotationFileStore = new AnnotationFileStore($storageDir . '/data.json');
$annotationService   = new AnnotationService($annotationFileStore);
$cacheService        = new CacheService($storageDir . '/cache.json');

$epubService = new EpubService();

$application = new Application();

$application->add(new AnnotationGetCommand($annotationService, $cacheService));
$application->add(new AnnotationRemoveCommand($annotationService));
$application->add(new AnnotationSetCommand($annotationService, $cacheService));

$application->add(new EpubListCommand($epubService, $annotationService));

$application->run();
