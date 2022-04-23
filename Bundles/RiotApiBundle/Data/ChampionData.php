<?php

declare(strict_types=1);

namespace Jesperbeisner\RiotApiBundle\Data;

class ChampionData
{
    public function __construct(
        public readonly int $id,
        public readonly string $stringId,
        public readonly string $name,
        public readonly string $title,
        public readonly string $blurb,
    ) {}
}
