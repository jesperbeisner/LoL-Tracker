<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Manager;

use Jesperbeisner\RiotApiBundle\Exception\RiotApiBundleException;
use Jesperbeisner\RiotApiBundle\Service\AbstractLoLApiService;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class RiotApiManager
{
    private string $riotApiKey;
    private array $services = [];

    public function __construct(
        string $riotApiKey,
        private readonly HttpClientInterface $httpClient,
        private readonly CacheInterface $cache,
        private readonly LoggerInterface $logger,
    )
    {
        if ('PLACEHOLDER' === $riotApiKey) {
            throw new RiotApiBundleException('You forgot to set your RIOT_API_KEY in your .env.local file');
        }

        $this->riotApiKey = $riotApiKey;
    }

    /**
     * @param class-string $apiServiceClassName
     * @throws RiotApiBundleException
     */
    public function getApiService(string $apiServiceClassName): AbstractLoLApiService
    {
        if (array_key_exists($apiServiceClassName, $this->services)) {
            return $this->services[$apiServiceClassName];
        }

        if (!class_exists($apiServiceClassName)) {
            throw new RiotApiBundleException("Class '$apiServiceClassName' does not exist");
        }

        try {
            $apiService = new $apiServiceClassName(
                $this->riotApiKey,
                $this->httpClient,
                $this->cache,
                $this->logger
            );
        } catch (Throwable $e) {
            throw new RiotApiBundleException(
                sprintf(
                    "The RiotApiManager can only create classes of type '%s', class name '%s' given",
                    AbstractLoLApiService::class,
                    $apiServiceClassName
                )
            );
        }

        $this->services[$apiServiceClassName] = $apiService;

        return $apiService;
    }
}
