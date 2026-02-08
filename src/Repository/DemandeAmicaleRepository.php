<?php

namespace App\Repository;

use App\Entity\DemandeAmicale;
use App\Entity\User;
use App\Entity\Etablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeAmicale>
 */
class DemandeAmicaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeAmicale::class);
    }

    public function save(DemandeAmicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandeAmicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Trouver les demandes d’un utilisateur
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.beneficiaire = :user')
            ->setParameter('user', $user)
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Trouver les demandes pour un établissement (utile pour sous-admin)
    public function findByEtablissement(Etablissement $etab)
    {
        return $this->createQueryBuilder('d')
            ->join('d.beneficiaire', 'u')
            ->where('u.idEtablissement = :etabId')
            ->setParameter('etabId', $etab->getId())
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Compter par statut
    public function countByStatut(string $statut): int
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.statut = :statut')
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Trouver toutes les demandes triées par statut
    public function findAllOrderedByStatus()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.statut', 'ASC')
            ->addOrderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }
}