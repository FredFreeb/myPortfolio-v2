<?php

namespace App\Repository;

use App\Entity\Translation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Translation>
 */
class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Translation::class);
    }

    public function findOneByKey(string $key, string $domain = 'messages'): ?Translation
    {
        return $this->findOneBy(['translationKey' => $key, 'domain' => $domain]);
    }

    /**
     * Charge toutes les traductions, indexées par domaine puis par clé.
     *
     * @return array<string, array<string, Translation>>
     */
    public function findAllIndexed(): array
    {
        $all = $this->createQueryBuilder('t')->getQuery()->getResult();
        $indexed = [];
        foreach ($all as $t) {
            $indexed[$t->getDomain()][$t->getTranslationKey()] = $t;
        }
        return $indexed;
    }

    /**
     * Retourne un dictionnaire plat {clé: contenu} pour un locale donné,
     * pour un domaine donné. Utilisé par le traducteur décorateur.
     *
     * @return array<string, string>
     */
    public function findLocaleDictionary(string $locale, string $domain = 'messages'): array
    {
        $rows = $this->createQueryBuilder('t')
            ->select('t.translationKey', 't.contents')
            ->where('t.domain = :domain')
            ->setParameter('domain', $domain)
            ->getQuery()
            ->getArrayResult();

        $dict = [];
        foreach ($rows as $row) {
            $contents = $row['contents'] ?? [];
            if (isset($contents[$locale]) && $contents[$locale] !== '') {
                $dict[$row['translationKey']] = $contents[$locale];
            }
        }
        return $dict;
    }

    /**
     * Retourne toutes les traductions regroupées par section (effective).
     *
     * @return array<string, Translation[]>
     */
    public function findAllGroupedBySection(): array
    {
        $all = $this->createQueryBuilder('t')
            ->orderBy('t.section', 'ASC')
            ->addOrderBy('t.translationKey', 'ASC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($all as $t) {
            $grouped[$t->getEffectiveSection()][] = $t;
        }
        ksort($grouped);
        return $grouped;
    }

    /**
     * Statistiques de complétude par locale.
     *
     * @param string[] $locales
     * @return array<string, array{total:int, done:int, percent:int}>
     */
    public function getCompletionStats(array $locales): array
    {
        $all = $this->createQueryBuilder('t')->select('t.contents')->getQuery()->getArrayResult();
        $total = \count($all);

        $stats = [];
        foreach ($locales as $loc) {
            $done = 0;
            foreach ($all as $row) {
                $c = $row['contents'][$loc] ?? null;
                if ($c !== null && trim((string) $c) !== '') {
                    $done++;
                }
            }
            $stats[$loc] = [
                'total'   => $total,
                'done'    => $done,
                'percent' => $total > 0 ? (int) round($done / $total * 100) : 0,
            ];
        }
        return $stats;
    }

    public function save(Translation $t, bool $flush = true): void
    {
        $this->getEntityManager()->persist($t);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Translation $t, bool $flush = true): void
    {
        $this->getEntityManager()->remove($t);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
