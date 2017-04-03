<?php
namespace App\Repository;

use App\Model\Match\Match;
use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineMatchRepository
 *
 * @package App\Repository
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class DoctrineMatchRepository extends EntityRepository implements MatchRepository
{
    public function findOneOpen(): ?Match
    {
        $queryBuilder = $this
            ->createQueryBuilder('m')
            ->leftJoin('m.players', 'p')
            ->groupBy('m.id')
            ->having('count(m.id) <> m.slots');
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function persist(Match $match): void
    {
        $this->getEntityManager()->persist($match);
        $this->getEntityManager()->flush($match);
    }
}
