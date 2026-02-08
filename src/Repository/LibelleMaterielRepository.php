<?php

namespace App\Repository;

use App\Entity\LibelleMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibelleMateriel>
 */
class LibelleMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibelleMateriel::class);
    }

    // src/Repository/LibelleMaterielRepository.php

public function findByName(string $name): array
{
    return $this->createQueryBuilder('lm')
        ->where('UPPER(lm.nom) LIKE :name OR UPPER(lm.reference) LIKE :name')
        ->setParameter('name', '%' . strtoupper($name) . '%')
        ->orderBy('lm.nom', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
}
}
