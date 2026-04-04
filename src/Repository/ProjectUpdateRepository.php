<?php

namespace App\Repository;

use App\Entity\ProjectUpdate;
use App\Enum\ProjectAudience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectUpdate>
 */
class ProjectUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectUpdate::class);
    }

    /**
     * @return list<ProjectUpdate>
     */
    public function findPublishedByAudience(ProjectAudience $audience, ?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('projectUpdate')
            ->andWhere('projectUpdate.audience = :audience')
            ->andWhere('projectUpdate.isPublished = :published')
            ->setParameter('audience', $audience)
            ->setParameter('published', true)
            ->orderBy('projectUpdate.sortOrder', 'ASC')
            ->addOrderBy('projectUpdate.publishedAt', 'DESC');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        /** @var list<ProjectUpdate> $updates */
        $updates = $queryBuilder->getQuery()->getResult();

        return $updates;
    }
}
