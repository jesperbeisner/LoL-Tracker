<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ActiveMatchRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiveMatchRepository::class)]
#[ORM\Index(columns: ['game_id'], name: "game_id_index")]
class ActiveMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::BIGINT)]
    private int $gameId;

    #[ORM\ManyToOne(targetEntity: Summoner::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Summoner $summoner;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $active = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updated = null;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function update(): void
    {
        $this->updated = new DateTime();
    }

    public function open(): void
    {
        $this->active = true;
    }

    public function close(): void
    {
        $this->active = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getSummoner(): Summoner
    {
        return $this->summoner;
    }

    public function setSummoner(Summoner $summoner): void
    {
        $this->summoner = $summoner;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }
}
