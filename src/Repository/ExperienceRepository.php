<?php

namespace App\Repository;

use App\Entity\Experience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Experience>
 */
class ExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }

    /**
     * @return list<Experience>
     */
    public function findPublished(?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->andWhere('e.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('e.sortOrder', 'ASC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<Experience> $results */
        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }
}
