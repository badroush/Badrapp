<?php

namespace App\Service;

use App\Entity\Etablissement;
use App\Entity\ChapitreBudget;
use App\Repository\RessourceAffectationRepository;
use App\Repository\PanierAchatCollectifRepository;

class BudgetValidationService
{
    public function __construct(
        private RessourceAffectationRepository $ressourceRepo,
        private PanierAchatCollectifRepository $panierRepo,
    ) {
    }

    public function verifierSolde(Etablissement $etablissement, ChapitreBudget $chapitre, float $montant): bool
    {
        // Récupère le montant total affecté à cet établissement pour ce chapitre
        $ressourceAffectee = $this->ressourceRepo->getTotalAffecteParEtablissement($etablissement, $chapitre);

        // Récupère les dépenses déjà effectuées
        $depenses = $this->getDepensesEffectuees($etablissement, $chapitre);

        $soldeDisponible = $ressourceAffectee - $depenses;

        return $soldeDisponible >= $montant;
    }

    private function getDepensesEffectuees(Etablissement $etablissement, ChapitreBudget $chapitre): float
    {
        // Récupère tous les paniers validés pour cet établissement et ce chapitre
        $paniers = $this->panierRepo->createQueryBuilder('p')
            ->join('p.chapitreAchatsCollectif', 'c')
            ->where('p.etablissement = :etab')
            ->andWhere('c.chapitreBudget = :chapitre')
            ->andWhere('p.etat = :etat')
            ->setParameter('etab', $etablissement)
            ->setParameter('chapitre', $chapitre)
            ->setParameter('etat', 'valide')
            ->getQuery()
            ->getResult();

        $total = 0;
        foreach ($paniers as $panier) {
            $total += $panier->getTotal();
        }

        return $total;
    }
}