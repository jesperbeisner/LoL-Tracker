<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Data;

class BannedChampionData
{
    public function __construct(
        public readonly int $championId,
        public readonly int $teamId,
        public readonly int $pickTurn,
    ) {}
}
