<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Service;

use Jesperbeisner\RiotApiBundle\Exception\LoLApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractLoLApiService
{
    public function __construct(
        protected readonly string $riotApiKey,
        protected readonly HttpClientInterface $httpClient,
        protected readonly CacheInterface $cache,
        protected readonly LoggerInterface $logger,
    ) {}

    protected function makeRequest(string $url): ResponseInterface
    {
        return $this->httpClient->request('GET', $url, [
            'headers' => [
                'X-Riot-Token' => $this->riotApiKey,
            ],
        ]);
    }

    protected function request(string $url): array
    {
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'X-Riot-Token' => $this->riotApiKey,
            ],
        ]);

        if (200 === $response->getStatusCode()) {
            return $response->toArray();
        }

        $this->throwLoLApiException($response);
    }

    protected function throwLoLApiException(ResponseInterface $response): never
    {
        /** @var array<string, array<string, string|int>> $content */
        $content = $response->toArray(false);

        throw new LoLApiException($content['status']['status_code'] . ' - ' . $content['status']['message']);
    }
}
