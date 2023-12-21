<?php

namespace YgoProDeckClient\Http;

use YgoProDeckClient\Exception\HttpClientResponseException;
use YgoProDeckClient\Model\Model;
use YgoProDeckClient\Normalizer\Denormalizer;

class CurlHttpClient
{
    final public const OPTION_BASE_URI = "base_uri";

    private array $options;
    private Denormalizer $denormalizer;

    public function __construct(array $options = [])
    {
        $this->options = \array_merge($this->getDefaultOptions(), $options);
        $this->denormalizer = new Denormalizer();
    }

    public function request(string $uri, string $objectClass, array $query = [], array $deserializationContext = []): Response|Model|null
    {
        $curl = \curl_init();

        \curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getUri($uri, $query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = \curl_exec($curl);

        \curl_close($curl);

        $response = $this->buildResponse($response, $objectClass, $deserializationContext);

        if ($response instanceof Error) {
            throw new HttpClientResponseException($response);
        }

        return $response;
    }

    private function getUri(string $uri, array $query): string
    {
        $result = "";

        if (!empty($this->options['base_uri'])) {
            $result = $this->options['base_uri'] . '/';
        }

        $uri = $result . $uri;

        if (0 === \count($query)) {
            return $uri;
        }

        return $uri . '?' . \http_build_query($query);
    }

    private function buildResponse(string $response, string $objectClass, array $deserializationContext): Response|Error|Model|null
    {
        $decoded = \json_decode($response, true);

        if (null === $decoded) {
            throw new \RuntimeException('Cannot build response : invalid JSON');
        }

        if (isset($decoded['error'])) {
            return new Error($decoded['error']);
        }

        $pagination = null;

        // Sets pagination
        if (isset($decoded['meta'])) {
            $pagination = $this->denormalizer->denormalize($decoded['meta'], Pagination::class);
        }

        if ($deserializationContext['as_collection'] ?? true) {
            $result = [];

            foreach ($decoded['data'] ?? $decoded as $item) {
                $result[] = $this->denormalizer->denormalize($item, $objectClass);
            }

            return new Response($result, $pagination);
        }

        if (0 === \count($decoded)) {
            return null;
        }

        return $this->denormalizer->denormalize($decoded, $objectClass);
    }

    private function getDefaultOptions(): array
    {
        return [
            self::OPTION_BASE_URI => ''
        ];
    }
}