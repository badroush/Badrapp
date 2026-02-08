<?php

namespace App\Repository;

use App\Entity\MouvementStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Article;

/**
 * @extends ServiceEntityRepository<MouvementStock>
 */
class MouvementStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementStock::class);
    }

    //    /**
    //     * @return MouvementStock[] Returns an array of MouvementStock objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MouvementStock
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

   public function findStockSummary()
{
    // On part des ARTICLES, pas des mouvements
    $qb = $this->getEntityManager()->createQueryBuilder();
    $qb
        ->select('
            a.id as article_id,
            a.nom as article_nom,
            a.stock as stock_actuel,
            a.seuilalerte as seuil_alerte,
            c.nom as categorie_nom,
            COALESCE(SUM(CASE WHEN m.type = \'entree\' THEN m.quantite ELSE 0 END), 0) as total_entrees,
            COALESCE(SUM(CASE WHEN m.type = \'sortie\' THEN m.quantite ELSE 0 END), 0) as total_sorties
        ')
        ->from('App\Entity\Article', 'a')
        ->leftJoin('a.categorie', 'c')
        ->leftJoin('App\Entity\MouvementStock', 'm', 'WITH', 'm.article = a.id')
        ->groupBy('a.id, a.nom, a.stock, a.seuilalerte, c.nom')
        ->orderBy('a.nom', 'ASC');

    return $qb->getQuery()->getResult();
}

public function countMouvementsByArticle(Article $article): int
{
    return $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->where('m.article = :article')
        ->setParameter('article', $article)
        ->getQuery()
        ->getSingleScalarResult();
}

public function getDateEntreeMin($article): ?\DateTimeInterface
{
    $result = $this->createQueryBuilder('m')
        ->select('MIN(m.dateMouvement)')
        ->where('m.article = :article')
        ->andWhere('m.type = :type')
        ->setParameter('article', $article)
        ->setParameter('type', 'entree')
        ->getQuery()
        ->getSingleScalarResult();

    return $result ? new \DateTime($result) : null;
}
}
