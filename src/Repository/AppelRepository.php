<?php

namespace App\Repository;

use App\Entity\Appel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appel>
 */
class AppelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appel::class);
    }

    public function findByFilters(array $filters = []): array
{
    $qb = $this->createQueryBuilder('a');

    if (isset($filters['dateAppel']['gte'])) {
        $qb->andWhere('a.dateAppel >= :dateDebut')
           ->setParameter('dateDebut', $filters['dateAppel']['gte']);
    }
    if (isset($filters['dateAppel']['lte'])) {
        $qb->andWhere('a.dateAppel <= :dateFin')
           ->setParameter('dateFin', $filters['dateAppel']['lte']);
    }
    if (isset($filters['contactEmetteur'])) {
        $qb->andWhere('a.contactEmetteur = :contact')
           ->setParameter('contact', $filters['contactEmetteur']);
    }
    if (isset($filters['type'])) {
        $qb->andWhere('a.type = :type')
           ->setParameter('type', $filters['type']);
    }

    return $qb->orderBy('a.dateAppel', 'DESC')
              ->getQuery()
              ->getResult();
}
}
