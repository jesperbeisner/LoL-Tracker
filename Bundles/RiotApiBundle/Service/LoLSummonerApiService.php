<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Service;

use DateTime;
use Jesperbeisner\RiotApiBundle\Data\SummonerData;

class LoLSummonerApiService extends AbstractLoLApiService
{
    public function findSummonerByUsername(string $username, string $server): SummonerData
    {
        $url = "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-name/$username";

        /** @var array<string, string|int> $result */
        $result = $this->request($url);

        return $this->createNewSummoner($result);
    }

    public function findSummonerByPuuid(string $puuid, string $server): SummonerData
    {
        $url = "https://$server.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid";

        /** @var array<string, string|int> $result */
        $result = $this->request($url);

        return $this->createNewSummoner($result);
    }

    /**
     * @param array<string, string|int> $summonerData
     */
    private function createNewSummoner(array $summonerData): SummonerData
    {
        return new SummonerData(
            (string) $summonerData['id'],
            (string) $summonerData['accountId'],
            (string) $summonerData['puuid'],
            (string) $summonerData['name'],
            (int) $summonerData['profileIconId'],
            (new DateTime())->setTimestamp((int) ((int) $summonerData['revisionDate'] / 1000)),
            (int) $summonerData['summonerLevel'],
        );
    }
}
