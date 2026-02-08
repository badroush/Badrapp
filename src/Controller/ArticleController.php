<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\MouvementStockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
<<<<<<< HEAD
use Symfony\Component\Security\Http\Attribute\IsGranted;
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepo
    ): Response {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        //dump($article->getDateCreation());
        if ($form->isSubmitted() && $form->isValid()) {
            $reference = null;
            $attempts = 0;
            do {
                $reference = $article->generateReference();
                $existing = $entityManager->getRepository(Article::class)
            ->findOneBy(['reference' => $reference]);
        $attempts++;
        if ($attempts > 10) {
            throw new \RuntimeException('Impossible de générer une référence unique.');
        }
    } while ($existing);

    $article->setReference($reference);
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'تم إضافة المنتج بنجاح !');
            return $this->redirectToRoute('article_index', ['added' => 'article']);
        }

        $articles = $articleRepo->findAll();

        return $this->render('article/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }
    #[Route('/article/{id}/edit', name: 'article_edit')]
public function edit(
    Article $article,
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'تم تحديث المنتج بنجاح !');
        return $this->redirectToRoute('article_index');
    }

    return $this->render('article/edit.html.twig', [
        'form' => $form->createView(),
        'article' => $article,
    ]);
}
#[Route('/article/{id}/delete', name: 'article_delete', methods: ['DELETE'])]
public function delete(
    Article $article,
    MouvementStockRepository $mouvementRepo,
    EntityManagerInterface $entityManager
): Response {
    // Vérifier s'il y a des mouvements
    $nbMouvements = $mouvementRepo->countMouvementsByArticle($article);

    if ($nbMouvements > 0) {
        return $this->json([
            'success' => false,
            'message' => 'لا يمكن حذف هذا المنتج لأنه يحتوي على حركات مخزون. يرجى تصفير الرصيد أولاً.'
        ], 400);
    }else{

    $entityManager->remove($article);
    $entityManager->flush();
}
    return $this->json(['success' => true]);
}

#[Route('/article/create-ajax', name: 'article_create_ajax', methods: ['POST'])]
public function createAjax(Request $request, EntityManagerInterface $entityManager): Response
{
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Générer référence unique (comme avant)
        $reference = null;
        $attempts = 0;
        do {
            $reference = $article->generateReference();
            $existing = $entityManager->getRepository(Article::class)->findOneBy(['reference' => $reference]);
            $attempts++;
            if ($attempts > 10) throw new \RuntimeException('Impossible de générer une référence unique.');
        } while ($existing);
        $article->setReference($reference);

        $entityManager->persist($article);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'article' => [
                'id' => $article->getId(),
                'reference' => $article->getReference(),
                'nom' => $article->getNom(),
                'categorie' => $article->getCategorie()?->getNom() ?? '—',
                'fournisseur' => $article->getFournisseur()?->getNom() ?? '—',
                'stock' => $article->getStock(),
                'seuilAlerte' => $article->getSeuilAlerte(),
                'etat' => $article->getStock() < $article->getSeuilAlerte() ? 'alerte' : 'ok'
            ]
        ]);
    }

    // En cas d'erreur de validation
    $errors = [];
    foreach ($form->getErrors(true) as $error) {
        $errors[] = $error->getMessage();
    }

    return $this->json(['success' => false, 'errors' => $errors], 400);
}
<<<<<<< HEAD

#[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($article);
        $entityManager->flush();

        $this->addFlash('success', 'تم إضافة المنتج بنجاح.');

        return $this->redirectToRoute('article_index');
    }

    return $this->render('article/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
=======
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
}
