<?php

namespace App\Repository;

use App\Entity\ProfileLink;
use App\Enum\LinkCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfileLink>
 */
class ProfileLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileLink::class);
    }

    /**
     * @return list<ProfileLink>
     */
    public function findPublished(?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('pl')
            ->andWhere('pl.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('pl.sortOrder', 'ASC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<ProfileLink> $results */
        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }

    /**
     * @return list<ProfileLink>
     */
    public function findPublishedByCategory(LinkCategory $category, ?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('pl')
            ->andWhere('pl.isPublished = :published')
            ->andWhere('pl.category = :category')
            ->setParameter('published', true)
            ->setParameter('category', $category)
            ->orderBy('pl.sortOrder', 'ASC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<ProfileLink> $results */
        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }
}
