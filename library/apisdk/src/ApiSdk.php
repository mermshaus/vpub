<?php

declare(strict_types=1);

namespace merms\anno\apisdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class ApiSdk
{
    private string $baseUri;

    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @param mixed $value
     */
    public function addAnnotation(string $sha256sum, string $key, $value): array
    {
        $client = $this->getClient();

        $bodyString = json_encode([
            'method'     => 'annotation.add',
            'parameters' => ['sha256sum' => $sha256sum, 'key' => $key, 'value' => $value],
        ]);

        $response = $client->request('POST', '/', ['body' => $bodyString]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param mixed $value
     */
    public function getChecksumsByKeyAndValue(string $key, $value): array
    {
        $client = $this->getClient();

        $bodyString = json_encode([
            'method'     => 'checksums.get-by-key-and-value',
            'parameters' => ['key' => $key, 'value' => $value],
        ]);

        $response = $client->request('GET', '/', ['body' => $bodyString]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getRecord(string $sha256sum): ?array
    {
        $client = $this->getClient();

        $bodyString = json_encode(['method' => 'record.get', 'parameters' => ['sha256sum' => $sha256sum]]);

        try {
            $response = $client->request('GET', '/', ['body' => $bodyString]);
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === 404) {
                return null;
            }

            throw $exception;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getClient(): Client
    {
        return new Client(['base_uri' => $this->baseUri]);
    }
}
