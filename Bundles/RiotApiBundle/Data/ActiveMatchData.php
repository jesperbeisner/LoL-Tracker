<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Data;

class ActiveMatchData
{
    public const BLUE_SIDE = 100;
    public const RED_SIDE = 200;

    public function __construct(
        public readonly int $gameId,
        public readonly int $mapId,
        public readonly string $gameMode,
        public readonly string $gameType,
        public readonly int $gameQueueConfigId,
        public readonly array $participants,
        public readonly array $bannedChampions,
    ) {}
}
