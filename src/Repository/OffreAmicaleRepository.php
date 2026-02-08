<?php

namespace App\Repository;

use App\Entity\OffreAmicale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffreAmicale>
 */
class OffreAmicaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreAmicale::class);
    }

    public function save(OffreAmicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OffreAmicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Exemple : trouver les offres actives
    public function findActives()
    {
        return $this->createQueryBuilder('o')
            ->where('o.etat = :etat')
            ->setParameter('etat', 'active')
            ->orderBy('o.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}