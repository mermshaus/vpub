<?php

declare(strict_types=1);

namespace merms\anno\apisdk;

use GuzzleHttp\Client;

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

    public function getAnnotations(string $sha256sum): array
    {
        $client = $this->getClient();

        $bodyString = json_encode(['method' => 'annotations.get', 'parameters' => ['sha256sum' => $sha256sum]]);

        $response = $client->request('GET', '/', ['body' => $bodyString]);

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

    public function getRecord(string $sha256sum): array
    {
        $client = $this->getClient();

        $bodyString = json_encode(['method' => 'record.get', 'parameters' => ['sha256sum' => $sha256sum]]);

        $response = $client->request('GET', '/', ['body' => $bodyString]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function setAnnotations(string $sha256sum, array $annotations): void
    {
        $client = $this->getClient();

        $bodyString = json_encode([
            'method'     => 'annotations.set',
            'parameters' => ['sha256sum' => $sha256sum, 'annotations' => $annotations],
        ]);

        $client->request('POST', '/', ['body' => $bodyString]);
    }

    private function getClient(): Client
    {
        return new Client(['base_uri' => $this->baseUri]);
    }
}
