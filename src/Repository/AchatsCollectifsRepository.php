<?php

namespace App\Repository;

use App\Entity\DetailAchatsCollectif; // Remplace par ton entité principale
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailAchatsCollectif>
 */
class AchatsCollectifsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailAchatsCollectif::class);
    }

    public function findDistinctArticles(): array
    {
        $result = $this->createQueryBuilder('d')
            ->select('DISTINCT d.article')
            ->where('d.article IS NOT NULL')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'article');
    }

    public function getQuantitiesByEtablissementAndArticle(): array
    {
        $result = $this->createQueryBuilder('d')
            ->select('e.id as etab_id, d.article, SUM(d.quantite) as total_quantite')
            ->leftJoin('d.etablissement', 'e')
            ->groupBy('e.id, d.article')
            ->getQuery()
            ->getScalarResult();

        $quantites = [];
        foreach ($result as $row) {
            $quantites[$row['etab_id']][$row['article']] = $row['total_quantite'];
        }

        return $quantites;
    }
}