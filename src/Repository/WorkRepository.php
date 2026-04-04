<?php

namespace App\Repository;

use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Work>
 */
class WorkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Work::class);
    }

    /**
     * @return list<Work>
     */
    public function findPublished(?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('work')
            ->andWhere('work.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('work.sortOrder', 'ASC')
            ->addOrderBy('work.publishedAt', 'DESC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<Work> $works */
        $works = $queryBuilder->getQuery()->getResult();

        return $works;
    }
}
