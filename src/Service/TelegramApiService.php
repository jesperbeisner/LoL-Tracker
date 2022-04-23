<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Summoner;
use Jesperbeisner\RiotApiBundle\Data\ActiveMatchData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramApiService
{
    private string $telegramApiKey;
    private string $telegramChatId;
    private HttpClientInterface $httpClient;

    public function __construct(string $telegramApiKey, string $telegramChatId, HttpClientInterface $httpClient)
    {
        if ('PLACEHOLDER' === $telegramApiKey) {
            throw new \Exception('You forgot to set your TELEGRAM_API_KEY in your .env.local file');
        }

        if ('PLACEHOLDER' === $telegramChatId) {
            throw new \Exception('You forgot to set your TELEGRAM_CHAT_ID in your .env.local file');
        }

        $this->telegramApiKey = $telegramApiKey;
        $this->telegramChatId = $telegramChatId;
        $this->httpClient = $httpClient;
    }

    public function sendSummonerStartedGameMessage(Summoner $summoner, ActiveMatchData $activeMatchData): bool
    {
        return $this->sendMessage('Game started: ' . $activeMatchData->gameId);
    }

    private function sendMessage(string $message): bool
    {
        $url = "https://api.telegram.org/bot{$this->telegramApiKey}/sendMessage";

        $result = $this->httpClient->request('POST', $url, [
            'body' => [
                'chat_id' => $this->telegramChatId,
                'text' => $message,
                'parse_mode' => 'MarkdownV2',
            ],
        ]);

        if (200 === $result->getStatusCode()) {
            return true;
        }

        return false;
    }
}
