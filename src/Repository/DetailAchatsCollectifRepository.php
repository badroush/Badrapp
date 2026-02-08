<?php

namespace App\Repository;

use App\Entity\DetailAchatsCollectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailAchatsCollectif>
 */
class DetailAchatsCollectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailAchatsCollectif::class);
    }
   public function findDistinctArticles(): array
    {
        $result = $this->createQueryBuilder('d')
            ->select('a.id, a.nom')
            ->leftJoin('d.article', 'a')
            ->where('d.article IS NOT NULL')
            ->groupBy('a.id, a.nom')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }

    public function getQuantitiesByEtablissementAndArticle(): array
    {
        $result = $this->createQueryBuilder('d')
            ->select('e.id as etab_id, a.id as article_id, SUM(ip.quantite) as total_quantite')
            ->join('d.article', 'a') // ✅ Jointure vers Article
            ->join('App\Entity\ItemPanierAchatCollectif', 'ip', 'WITH', 'ip.detailAchatCollectif = d.id') // ✅ Jointure avec ItemPanier
            ->join('ip.panier', 'p') // ✅ Jointure vers Panier
            ->join('p.etablissement', 'e') // ✅ Jointure vers Etablissement
            ->groupBy('e.id, a.id') // ✅ Groupe par établissement et article
            ->getQuery()
            ->getScalarResult();

        $quantites = [];
        foreach ($result as $row) {
            $quantites[$row['etab_id']][$row['article_id']] = $row['total_quantite'];
        }

        return $quantites;
    }
}
