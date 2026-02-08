<?php

namespace App\Repository;

use App\Entity\AssociationSportif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssociationSportif>
 */
class AssociationSportifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationSportif::class);
    }

    public function searchByNameOrDelegation(string $query): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.delegation', 'd')
        ->where('a.nom LIKE :query')
        ->orWhere('d.nom LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
}
