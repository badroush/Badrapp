<?php

namespace App\Repository;

use App\Entity\ActionControle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ActionControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionControle::class);
    }
  public function findByRoleAndAction(string $role, string $action): ?ActionControle
{
    $results = $this->createQueryBuilder('ac')
        ->where('ac.action = :action')
        ->setParameter('action', $action)
        ->getQuery()
        ->getResult();

    foreach ($results as $control) {
        if (in_array($role, $control->getRoles(), true)) {
            return $control;
        }
    }
    return null;
}
}