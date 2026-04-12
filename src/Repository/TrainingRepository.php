<?php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Training>
 */
class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    /**
     * @return list<Training>
     */
    public function findPublished(?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->andWhere('t.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('t.sortOrder', 'ASC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<Training> $results */
        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }
}
