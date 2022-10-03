<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Checksum;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';

$dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
    $r->addRoute(['GET', 'POST'], '/', 'null');

    $r->addRoute('GET', '/users', 'get_all_users_handler');
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri        = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== ($pos = strpos($uri, '?'))) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars    = $routeInfo[2];

        $entityBody = file_get_contents('php://input');

        $data = json_decode($entityBody, true);

        file_put_contents('php://stdout', sprintf('Request received: %s', $entityBody));

        if ($data['method'] === 'annotation.add') {
            if ($httpMethod !== 'POST') {
                throw new \RuntimeException('POST request expected');
            }

            $class  = new AnnotationAdd();
            $record = $class->execute(
                new Checksum($data['parameters']['sha256sum']),
                $data['parameters']['key'],
                $data['parameters']['value'],
            );

            echo json_encode($record->toJsonArray());
        } elseif ($data['method'] === 'checksums.get-by-key-and-value') {
            if ($httpMethod !== 'GET') {
                throw new \RuntimeException('GET request expected');
            }

            $class = new ChecksumsGetByKeyAndValue();
            echo json_encode($class->execute($data['parameters']['key'], $data['parameters']['value']));
        } elseif ($data['method'] === 'record.get') {
            if ($httpMethod !== 'GET') {
                throw new \RuntimeException('GET request expected');
            }

            $class  = new RecordGet();
            $record = $class->execute(new Checksum($data['parameters']['sha256sum']));

            if ($record === null) {
                $response = new Response(
                    '404',
                    Response::HTTP_NOT_FOUND,
                    ['content-type' => 'application/json']
                );
            } else {
                $response = new Response(
                    json_encode($record->toJsonArray()),
                    Response::HTTP_OK,
                    ['content-type' => 'application/json']
                );
            }

            $response->send();
        }

        // ... call $handler with $vars
        break;
}
