<?php

namespace App\Repository;

use App\Entity\PanierAchatCollectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\ChapitreBudget;
use App\Entity\Etablissement;
use App\Entity\Budget;

/**
 * @extends ServiceEntityRepository<PanierAchatCollectif>
 */
class PanierAchatCollectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierAchatCollectif::class);
    }


    public function getTotalAchats(): float
{
    $result = $this->createQueryBuilder('p')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->select('SUM(i.quantite * dac.prixVente) as total')
        ->getQuery()
        ->getSingleScalarResult();

    return $result ?: 0;
}
public function getTotalAchatsByEtablissement(): array
{
    $result = $this->createQueryBuilder('p')
        ->join('p.etablissement', 'e')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->select('e.nom as etablissement, SUM(i.quantite * dac.prixVente) as total')
        ->groupBy('e.id')
        ->getQuery()
        ->getResult();

    return array_map(function ($item) {
        return [
            'etablissement' => $item['etablissement'],
            'total' => $item['total'] ?: 0,
        ];
    }, $result);
}

public function getTotalAchatsByChapitre(): array
{
    $result = $this->createQueryBuilder('p')
        ->join('p.chapitreAchatsCollectif', 'c')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->select('c.nom as chapitre, SUM(i.quantite * dac.prixVente) as total')
        ->groupBy('c.id')
        ->getQuery()
        ->getResult();

    return array_map(function ($item) {
        return [
            'chapitre' => $item['chapitre'],
            'total' => $item['total'] ?: 0,
        ];
    }, $result);
}

public function getAchatsMensuels(): array
{
    $qb = $this->createQueryBuilder('p')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->select('p.createdAt as date, SUM(i.quantite * dac.prixVente) as total')
        ->groupBy('p.createdAt');

    // Filtre par année en PHP
    $result = $qb->getQuery()->getResult();
    $year = date('Y');

    $data = array_fill(1, 12, 0); // Initialiser les 12 mois à 0

    foreach ($result as $item) {
        if ($item['date']->format('Y') == $year) {
            $mois = (int)$item['date']->format('n'); // n = mois sans zéro (1-12)
            $data[$mois] += $item['total'] ?: 0;
        }
    }

    return array_values($data);
}

public function getBudgetsUtilises(): array
{
    $result = $this->createQueryBuilder('p')
        ->join('p.chapitreAchatsCollectif', 'c')
        ->join('c.chapitreBudget', 'cb')
        ->join('cb.budgets', 'b')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->select('cb.nom as chapitre, SUM(i.quantite * dac.prixVente) as total')
        ->groupBy('cb.id')
        ->orderBy('total', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();

    return array_map(function ($item) {
        return [
            'chapitre' => $item['chapitre'],
            'total' => $item['total'] ?: 0,
        ];
    }, $result);
}

public function getBudgetsByEtablissement(int $etabId): array
{
    $result = $this->createQueryBuilder('p')
        ->join('p.etablissement', 'e')
        ->join('p.chapitreAchatsCollectif', 'c')
        ->join('c.chapitreBudget', 'cb')
        ->join('cb.budgets', 'b')
        ->select('e.id as etab_id, e.nom as etab_nom, cb.id as cb_id, cb.code as cb_code, cb.nom as cb_nom, b.montant as montant')
        ->where('e.id = :etabId')
        ->setParameter('etabId', $etabId)
        ->groupBy('cb.id')
        ->getQuery()
        ->getResult();

    // Convertis les résultats en objets
    $budgets = [];
    foreach ($result as $item) {
        $etablissement = new Etablissement(); // Remplace par ton entité
        $etablissement->setId($item['etab_id']);
        $etablissement->setNom($item['etab_nom']);

        $chapitreBudget = new ChapitreBudget(); // Remplace par ton entité
        $chapitreBudget->setId($item['cb_id']);
        $chapitreBudget->setCode($item['cb_code']);
        $chapitreBudget->setNom($item['cb_nom']);

        $budget = new Budget(); // Remplace par ton entité
        $budget->setMontant($item['montant']);

        $budgets[] = [
            'etablissement' => $etablissement,
            'chapitreBudget' => $chapitreBudget,
            'budget' => $budget,
        ];
    }

    return $budgets;
}

public function getBudgetsGlobal(): array
{
    $result = $this->createQueryBuilder('p')
        ->join('p.etablissement', 'e')
        ->join('p.chapitreAchatsCollectif', 'c')
        ->join('c.chapitreBudget', 'cb')
        ->join('cb.budgets', 'b')
        ->select('cb.id as chapitre_budget_id, cb.code as code, cb.nom as nom, SUM(b.montant) as total_montant')
        ->groupBy('cb.id')
        ->getQuery()
        ->getResult();

    // Convertis les résultats en objets
    $budgets = [];
    foreach ($result as $item) {
        $chapitreBudget = new ChapitreBudget(); // Remplace par ton entité
        $chapitreBudget->setId($item['chapitre_budget_id']);
        $chapitreBudget->setCode($item['code']);
        $chapitreBudget->setNom($item['nom']);

        $budgets[] = [
            'chapitreBudget' => $chapitreBudget,
            'total_montant' => $item['total_montant'],
        ];
    }

    return $budgets;
}
public function getAchatsCollectifsAvecDetails(?int $etabId = null, ?int $chapitreId = null): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('a.nom as article_nom, SUM(i.quantite) as total_quantite, dac.prixVente as prix_vente')
        ->join('p.items', 'i')
        ->join('i.detailAchatCollectif', 'dac')
        ->join('dac.article', 'a') // ✅ Correction ici
        ->groupBy('a.id');

    if ($etabId) {
        $qb->andWhere('p.etablissement = :etabId')
           ->setParameter('etabId', $etabId);
    }

    if ($chapitreId) {
        $qb->andWhere('p.chapitreAchatsCollectif = :chapitreId')
           ->setParameter('chapitreId', $chapitreId);
    }

    return $qb->getQuery()->getResult();
}

public function getAchatsParEtablissement(): array
{
    return $this->createQueryBuilder('p')
        ->select('e.id as etab_id, a.id as article_id, a.nom as article_nom, SUM(ip.quantite) as quantite')
        ->join('p.items', 'ip') // Joindre les items du panier
        ->join('ip.detailAchatCollectif', 'dac') // Joindre le détail d'achat
        ->join('dac.article', 'a') // Joindre l'article
        ->join('p.etablissement', 'e') // Joindre l'établissement
        ->groupBy('e.id', 'a.id')
        ->getQuery()
        ->getResult();
}
}