<?php

namespace App\Repository;

use App\Entity\SeancePlenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeancePlenaire>
 */
class SeancePlenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeancePlenaire::class);
    }

   public function searchByAssociationOrDate(string $query): array
{
    return $this->createQueryBuilder('s')
        ->leftJoin('s.association', 'a')
        ->where('a.nom LIKE :query')
        ->orWhere('s.date LIKE :query')
        ->orWhere('s.type LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
}
