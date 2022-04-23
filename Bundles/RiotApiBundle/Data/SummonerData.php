<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Data;

use DateTime;

class SummonerData
{
    public function __construct(
        public readonly string $id,
        public readonly string $accountId,
        public readonly string $puuid,
        public readonly string $name,
        public readonly int $profileIconId,
        public readonly DateTime $revisionDate,
        public readonly int $summonerLevel,
    ) {}
}
