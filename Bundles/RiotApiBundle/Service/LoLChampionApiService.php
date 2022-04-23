<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Service;

use Jesperbeisner\RiotApiBundle\Data\ChampionData;
use Symfony\Contracts\Cache\ItemInterface;

class LoLChampionApiService extends AbstractLoLApiService
{
    /**
     * @return ChampionData[]
     */
    public function getAllChampions(): array
    {
        /** @var ChampionData[] $result */
        $result = $this->cache->get('champions', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            $versionsUrl = "https://ddragon.leagueoflegends.com/api/versions.json";
            $versionsResult = $this->request($versionsUrl);

            /** @var string $currentVersion */
            $currentVersion = $versionsResult[0];

            $championsUrl = "https://ddragon.leagueoflegends.com/cdn/$currentVersion/data/de_DE/champion.json";
            $championsResult = $this->request($championsUrl);

            $result = [];
            foreach ($championsResult['data'] as $championData) {
                $result[(int) $championData['key']] = new ChampionData(
                    (int) $championData['key'],
                    (string) $championData['id'],
                    (string) $championData['name'],
                    (string) $championData['title'],
                    (string) $championData['blurb'],
                );
            }

            return $result;
        });

        return $result;
    }
}
