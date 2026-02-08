<?php

namespace App\Repository;

use App\Entity\RessourceAffectation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Etablissement;
use phpDocumentor\Reflection\Types\Integer;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<RessourceAffectation>
 */
class RessourceAffectationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RessourceAffectation::class);
    }
    public function getTotalAffecteParEtablissement(Etablissement $etab): float
{
    $result = $this->createQueryBuilder('ra')
        ->select('SUM(ra.montant) as total')
        ->where('ra.idEtablissement = :etablissement')
        ->setParameter('etablissement', $etab) 
        ->getQuery()
        ->getSingleScalarResult();

    return $result !== null ? (float)$result : 0.0;
}

public function findForUser(User $user): array
{
    $qb = $this->createQueryBuilder('ra');

    // Si l'utilisateur est ADMIN → pas de filtre
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        return $qb->getQuery()->getResult();
    }

    // Sinon (sous-admin / user normal) → filtrer par son établissement
    $etablissement = $user->getEtablissement();
    if (!$etablissement) {
        return []; // ou throw exception
    }

    return $qb
        ->where('ra.idEtablissement = :etabId')
        ->setParameter('etabId', $etablissement->getId())
        ->getQuery()
        ->getResult();
}
}
