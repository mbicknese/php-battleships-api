<?php
namespace App\Repository;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineMatchRepository
 *
 * @package App\Repository
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class DoctrineMatchRepository extends EntityRepository implements MatchRepository
{
    /**
     * @return Match|null
     */
    public function findOneOpen(): ?Match
    {
        $queryBuilder = $this
            ->createQueryBuilder('m')
            ->leftJoin('m.players', 'p')
            ->groupBy('m.id')
            ->having('count(m.id) <> m.slots');
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param MatchId $matchId
     * @return Match|null|object
     */
    public function findOneById(MatchId $matchId): ?Match
    {
        return $this->findOneBy(['id' => $matchId]);
    }

    /**
     * @param Match $match
     */
    public function persist(Match $match): void
    {
        $this->getEntityManager()->persist($match);
        $this->getEntityManager()->flush($match);
    }
}
