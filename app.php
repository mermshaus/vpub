#!/usr/bin/env php
<?php
declare(strict_types=1);

namespace merms\vpub;

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$annotationFileStore = new AnnotationFileStore(__DIR__ . '/data.json');
$annotationService   = new AnnotationService($annotationFileStore);

$epubService = new EpubService();

$application = new Application();

$application->add(new AnnotationGetCommand($annotationService));
$application->add(new AnnotationRemoveCommand($annotationService));
$application->add(new AnnotationSetCommand($annotationService));

$application->add(new EpubListCommand($epubService, $annotationService));

$application->run();
