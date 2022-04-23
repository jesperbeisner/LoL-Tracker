<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Data;

class ParticipantData
{
    public function __construct(
        public readonly int $teamId,
        public readonly int $spell1Id,
        public readonly int $spell2Id,
        public readonly int $championId,
        public readonly string $summonerName,
        public readonly string $lolSummonerId,
    ) {}
}
