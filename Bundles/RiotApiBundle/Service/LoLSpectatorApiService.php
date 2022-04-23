<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Service;

use Jesperbeisner\RiotApiBundle\Data\ActiveMatchData;
use Jesperbeisner\RiotApiBundle\Data\BannedChampionData;
use Jesperbeisner\RiotApiBundle\Data\ParticipantData;

class LoLSpectatorApiService extends AbstractLoLApiService
{
    public function findActiveMatch(string $summonerId, string $server): ?ActiveMatchData
    {
        $url = "https://$server.api.riotgames.com/lol/spectator/v4/active-games/by-summoner/$summonerId";

        $response = $this->makeRequest($url);

        if (200 === $response->getStatusCode()) {
            $activeMatch = $response->toArray();

            $participants = [];
            foreach ($activeMatch['participants'] as $participant) {
                $participants[] = new ParticipantData(
                    (int) $participant['teamId'],
                    (int) $participant['spell1Id'],
                    (int) $participant['spell2Id'],
                    (int) $participant['championId'],
                    (string) $participant['summonerName'],
                    (string) $participant['summonerId'],
                );
            }

            $bannedChampions = [];
            foreach ($activeMatch['bannedChampions'] as $bannedChampion) {
                $bannedChampions[] = new BannedChampionData(
                    (int) $bannedChampion['championId'],
                    (int) $bannedChampion['teamId'],
                    (int) $bannedChampion['pickTurn'],
                );
            }

            return new ActiveMatchData(
                (int) $activeMatch['gameId'],
                (int) $activeMatch['mapId'],
                (string) $activeMatch['gameMode'],
                (string) $activeMatch['gameType'],
                (int) $activeMatch['gameQueueConfigId'],
                $participants,
                $bannedChampions,
            );
        }

        if (404 === $response->getStatusCode()) {
            return null;
        }

        $this->throwLoLApiException($response);
    }
}
