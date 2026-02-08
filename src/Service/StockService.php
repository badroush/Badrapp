<?php
// src/Service/StockService.php

namespace App\Service;
use App\Entity\Article;
use App\Entity\MouvementStock;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Repository\MouvementStockRepository;
class StockService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MouvementStockRepository $mouvementRepo
    ) {}

    /**
     * Met à jour le stock d’un article à partir d’un mouvement.
     * Valide la cohérence du mouvement avant application.
     */
    public function appliquerMouvement(MouvementStock $mouvement): void
    {
        $article = $mouvement->getArticle();
        $type = $mouvement->getType();
        $quantite = $mouvement->getQuantite();
        $dateMouvement = $mouvement->getDateMouvement();

        // Validation du type
        if (!in_array($type, ['entree', 'sortie'])) {
            throw new Exception("Type de mouvement invalide : '$type'. Doit être 'entree' ou 'sortie'.");
        }

        // Vérifier la date de sortie
        if ($type === 'sortie') {
            $dateEntreeMin = $this->mouvementRepo->getDateEntreeMin($article);

            if ($dateEntreeMin && $dateMouvement < $dateEntreeMin) {
                throw new Exception("تاريخ الخروج لا يمكن أن يكون أقدم من تاريخ الدخول.");
            }
        }
        // Validation des relations selon le type
        if ($type === 'entree') {
            if (!$mouvement->getFournisseur()) {
                throw new Exception("دخول يجب أن يكون له مورد.");
            }
            if ($mouvement->getBeneficiaire()) {
                throw new Exception("دخول يجب أن لا يكون له مستفيد.");
            }
            $article->setStock($article->getStock() + $quantite);
        } elseif ($type === 'sortie') {
            if (!$mouvement->getBeneficiaire()) {
                throw new Exception("مخرج يجب أن يكون له مستفيد.");
            }
            if ($mouvement->getFournisseur()) {
                throw new Exception("مخرج يجب أن لا يكون له مورد.");
            }
            if ($quantite > $article->getStock()) {
                throw new Exception("كمية غير كافية.");
            }
            $article->setStock($article->getStock() - $quantite);
        }

        // Sauvegarde de l’article mis à jour
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }
}
