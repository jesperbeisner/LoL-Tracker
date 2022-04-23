<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SummonerRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Jesperbeisner\RiotApiBundle\Data\SummonerData;

#[ORM\Entity(repositoryClass: SummonerRepository::class)]
#[ORM\Index(columns: ['username'], name: 'username_index')]
#[ORM\Index(columns: ['lol_id'], name: 'lol_id_index')]
#[ORM\Index(columns: ['lol_account_id'], name: 'lol_account_id_index')]
#[ORM\UniqueConstraint(name: 'lol_puuid_index', columns: ['lol_puuid'])]
class Summoner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    private string $notes;

    #[ORM\Column(type: Types::STRING)]
    private string $server;

    #[ORM\Column(type: Types::STRING)]
    private string $lolId;

    #[ORM\Column(type: Types::STRING)]
    private string $lolAccountId;

    #[ORM\Column(type: Types::STRING)]
    private string $lolPuuid;

    #[ORM\Column(type: Types::INTEGER)]
    private int $summonerLevel;

    #[ORM\Column(type: Types::INTEGER)]
    private int $profileIconId;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $active = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $revisionDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTime $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updated = null;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function setSummonerData(SummonerData $summonerData): void
    {
        $this->username = $summonerData->name;
        $this->lolId = $summonerData->id;
        $this->lolAccountId = $summonerData->accountId;
        $this->lolPuuid = $summonerData->puuid;
        $this->profileIconId = $summonerData->profileIconId;
        $this->revisionDate = $summonerData->revisionDate;
        $this->summonerLevel = $summonerData->summonerLevel;
    }

    public function getServerName(): string
    {
        return match ($this->server) {
            'euw1' => 'EUW',
            'na1' => 'NA',
            default => 'Unknown'
        };
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function deactivate(): void
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getNotes(): string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function setServer(string $server): void
    {
        $this->server = $server;
    }

    public function getLolId(): string
    {
        return $this->lolId;
    }

    public function setLolId(string $lolId): void
    {
        $this->lolId = $lolId;
    }

    public function getLolAccountId(): string
    {
        return $this->lolAccountId;
    }

    public function setLolAccountId(string $lolAccountId): void
    {
        $this->lolAccountId = $lolAccountId;
    }

    public function getLolPuuid(): string
    {
        return $this->lolPuuid;
    }

    public function setLolPuuid(string $lolPuuid): void
    {
        $this->lolPuuid = $lolPuuid;
    }

    public function getSummonerLevel(): int
    {
        return $this->summonerLevel;
    }

    public function setSummonerLevel(int $summonerLevel): void
    {
        $this->summonerLevel = $summonerLevel;
    }

    public function getProfileIconId(): int
    {
        return $this->profileIconId;
    }

    public function setProfileIconId(int $profileIconId): void
    {
        $this->profileIconId = $profileIconId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getRevisionDate(): DateTime
    {
        return $this->revisionDate;
    }

    public function setRevisionDate(DateTime $revisionDate): void
    {
        $this->revisionDate = $revisionDate;
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
