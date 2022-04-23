<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Summoner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Summoner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Summoner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Summoner[]    findAll()
 * @method Summoner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SummonerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Summoner::class);
    }

    public function findSummonerByPuuid(string $puuid): ?Summoner
    {
        return $this->findOneBy(['lolPuuid' => $puuid]);
    }

    /**
     * @return Summoner[]
     */
    public function findAllActiveSummoners(): array
    {
        return $this->findBy(['active' => true]);
    }
}
