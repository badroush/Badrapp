<?php

namespace App\Repository;

use App\Entity\ItemPanierAchatCollectif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemPanierAchatCollectif>
 */
class ItemPanierAchatCollectifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemPanierAchatCollectif::class);
    }

    
}
