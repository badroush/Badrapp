<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
<<<<<<< HEAD
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

/**
 * @extends ServiceEntityRepository<Materiel>
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

<<<<<<< HEAD
    public function findDistinctEmplacements(): array
    {
        $result = $this->createQueryBuilder('m')
            ->select('DISTINCT m.emplacement')
            ->where('m.emplacement IS NOT NULL')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'emplacement');
    }

    public function findDistinctEtats(): array
    {
        $result = $this->createQueryBuilder('m')
            ->select('DISTINCT m.etat')
            ->where('m.etat IS NOT NULL')
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'etat');
    }

    public function findByFilters(
        ?\DateTime $dateDebut,
        ?\DateTime $dateFin,
        ?string $etablissement,
        ?string $emplacement,
        ?string $etat,
        User $user,
        Security $security
    ): array {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.etablissement', 'e')
            ->leftJoin('m.libelleMateriel', 'lm'); // ✅ Ajout de la jointure

        // Filtre par utilisateur (non admin voit seulement son établissement)
        if (!($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPER_ADMIN'))) {
            if ($user->getEtablissement()) {
                $qb->andWhere('m.etablissement = :etab_user')
                   ->setParameter('etab_user', $user->getEtablissement());
            }
        }

        // Filtres
        if ($dateDebut) {
            $qb->andWhere('m.dateAcquisition >= :date_debut')
               ->setParameter('date_debut', $dateDebut);
        }
        if ($dateFin) {
            $qb->andWhere('m.dateAcquisition <= :date_fin')
               ->setParameter('date_fin', $dateFin);
        }
        if ($etablissement) {
            $qb->andWhere('e.id = :etablissement')
               ->setParameter('etablissement', $etablissement);
        }
        if ($emplacement) {
            $qb->andWhere('m.emplacement = :emplacement')
               ->setParameter('emplacement', $emplacement);
        }
        if ($etat) {
            $qb->andWhere('m.etat = :etat')
               ->setParameter('etat', $etat);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByEtablissement($etablissement): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.libelleMateriel', 'lm') // ✅ Ajout de la jointure
            ->andWhere('m.etablissement = :etablissement')
            ->setParameter('etablissement', $etablissement)
            ->orderBy('lm.nom', 'ASC') // ✅ Tri par nom du libellé
            ->getQuery()
            ->getResult();
    }

    // ✅ Mise à jour pour chercher dans les libellés
    public function findSimilarNames(string $q): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.libelleMateriel', 'lm') // ✅ Jointure
            ->select('DISTINCT lm.nom') // ✅ Sélection depuis libelleMateriel
            ->where('UPPER(lm.nom) LIKE :q') // ✅ Condition sur le nom du libellé
            ->setParameter('q', '%' . strtoupper($q) . '%')
            ->orderBy('lm.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }

    public function findSimilarEmplacements(string $q): array
    {
        return $this->createQueryBuilder('m')
            ->select('DISTINCT m.emplacement')
            ->where('m.emplacement IS NOT NULL')
            ->andWhere('UPPER(m.emplacement) LIKE :q')
            ->setParameter('q', '%' . strtoupper($q) . '%')
            ->orderBy('m.emplacement', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getScalarResult();
    }
}
=======
    //    /**
    //     * @return Materiel[] Returns an array of Materiel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Materiel
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByEtablissement($etablissement): array
{
    return $this->createQueryBuilder('m')
        ->andWhere('m.etablissement = :etablissement')
        ->setParameter('etablissement', $etablissement)
        ->orderBy('m.nom', 'ASC')
        ->getQuery()
        ->getResult();
}

// Recherche de noms similaires (insensible à la casse, avec LIKE)
public function findSimilarNames(string $q): array
{
    return $this->createQueryBuilder('m')
        ->select('DISTINCT m.nom')
        ->where('UPPER(m.nom) LIKE :q')
        ->setParameter('q', '%' . strtoupper($q) . '%')
        ->orderBy('m.nom', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getScalarResult();
}

// Recherche d'emplacements similaires
public function findSimilarEmplacements(string $q): array
{
    return $this->createQueryBuilder('m')
        ->select('DISTINCT m.emplacement')
        ->where('m.emplacement IS NOT NULL')
        ->andWhere('UPPER(m.emplacement) LIKE :q')
        ->setParameter('q', '%' . strtoupper($q) . '%')
        ->orderBy('m.emplacement', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getScalarResult();
}
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
