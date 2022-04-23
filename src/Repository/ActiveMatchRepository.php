<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ActiveMatch;
use App\Entity\Summoner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method ActiveMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActiveMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActiveMatch[]    findAll()
 * @method ActiveMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiveMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActiveMatch::class);
    }

    public function findActiveMatchForSummoner(Summoner $summoner): ?ActiveMatch
    {
        $activeMatches = $this->findBy(['summoner' => $summoner, 'active' => true]);

        if (1 < count($activeMatches)) {
            throw new Exception('Only one match at a time can be open for a specific summoner. Somehow a match did not get closed.');
        }

        if (1 === count($activeMatches)) {
            return $activeMatches[0];
        }

        return null;
    }
}
