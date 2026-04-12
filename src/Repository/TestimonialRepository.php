<?php

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /**
     * @return list<Testimonial>
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

        /** @var list<Testimonial> $results */
        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }
}
