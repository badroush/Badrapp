<?php

namespace App\Repository;

use App\Entity\Budget;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Etablissement;

/**
 * @extends ServiceEntityRepository<Budget>
 */
class BudgetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Budget::class);
    }
public function getTotalBudgetsByEtablissement(Etablissement $etablissement): float
{
    $result = $this->createQueryBuilder('b')
        ->select('SUM(b.montant)')
        ->where('b.idEtablissement = :etab')
        ->setParameter('etab', $etablissement->getId())
        ->getQuery()
        ->getSingleScalarResult();

    return $result !== null ? (float) $result : 0.0;
}

}
