<?php

namespace App\Controller;

use App\Entity\DemandeAmicale;
use App\Entity\OffreAmicale;
use App\Entity\FichierJoint;
use App\Form\DemandeAmicaleType;
use App\Form\OffreAmicaleType;
use App\Repository\DemandeAmicaleRepository;
use App\Repository\OffreAmicaleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/amicale')]
class AmicaleController extends AbstractController
{
    #[Route('/', name: 'app_amicale_index', methods: ['GET'])]
    public function index(OffreAmicaleRepository $offreRepo, DemandeAmicaleRepository $demandeRepo, UserRepository $userRepo, Security $security): Response
    {
        $user = $security->getUser();
        $is_admin = in_array('ROLE_ADMIN', $user->getRoles());

        $offres = $offreRepo->findAll();

        $demandes = $is_admin
            ? $demandeRepo->findAll()
            : $demandeRepo->findBy(['beneficiaire' => $user]);

        return $this->render('amicale/index.html.twig', [
            'offres' => $offres,
            'demandes' => $demandes,
            'is_admin' => $is_admin,
        ]);
    }

    #[Route('/offres', name: 'app_amicale_offres', methods: ['GET'])]
    public function offres(OffreAmicaleRepository $repo): Response
    {
        $offres = $repo->findAll();
        return $this->render('amicale/offres.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/demande/nouvelle', name: 'app_demande_nouvelle', methods: ['GET', 'POST'])]
    public function nouvelleDemande(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $demande = new DemandeAmicale();
        $user = $security->getUser();
        //dd($user->getRoles()); // Voir les rôles exacts de l'utilisateur
        $form = $this->createForm(DemandeAmicaleType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offre = $demande->getOffre();

            if ($offre->getEtat() !== 'active') {
                $this->addFlash('error', 'هذا العرض غير متوفر حالياً.');
                return $this->redirectToRoute('app_demande_nouvelle');
            }

           // $demande->setBeneficiaire($user);
            $demande->setStatut('en_attente');

            $em->persist($demande);
            $em->flush();

            $this->addFlash('success', 'تم إرسال الطلب بنجاح.');
            return $this->redirectToRoute('app_amicale_index');
        }

        return $this->render('amicale/nouvelle_demande.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin', name: 'app_amicale_admin', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(
        OffreAmicaleRepository $offreRepo,
        DemandeAmicaleRepository $demandeRepo
    ): Response {
        $offres = $offreRepo->findAll();
        $demandes = $demandeRepo->findAll();

        // Statistiques
        $stats = [];
        foreach (['en_attente', 'valide', 'refuse', 'paye'] as $statut) {
            $stats[$statut] = $demandeRepo->countByStatut($statut);
        }

        return $this->render('amicale/admin.html.twig', [
            'offres' => $offres,
            'demandes' => $demandes,
            'stats' => $stats,
        ]);
    }

    #[Route('/demande/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editDemande(DemandeAmicale $demande, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($demande)
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'في الانتظار' => 'en_attente',
                    'مقبول' => 'valide',
                    'مرفوض' => 'refuse',
                    'مدفوع' => 'paye'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'تم تحديث الحالة بنجاح.');
            return $this->redirectToRoute('app_amicale_index');
        }

        return $this->render('amicale/edit_demande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/demande/{id}/show', name: 'app_demande_show', methods: ['GET'])]
    public function showDemande(DemandeAmicale $demande, Security $security): Response
    {
        $user = $security->getUser();
        $is_admin = in_array('ROLE_ADMIN', $user->getRoles());
        $is_magasin = in_array('ROLE_MAGASIN', $user->getRoles());

        // Sécurité : seul l'utilisateur ou l'admin peut voir la demande
        if (!$is_admin && $demande->getBeneficiaire() !== $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('amicale/show_demande.html.twig', [
            'demande' => $demande,
        ]);
    }


    #[Route('/offre/nouvelle', name: 'app_offre_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newOffre(Request $request, EntityManagerInterface $em): Response
    {
        $offre = new OffreAmicale();
        $form = $this->createForm(OffreAmicaleType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offre);
            $em->flush();
            $this->addFlash('success', 'تم إنشاء العرض بنجاح.');
            return $this->redirectToRoute('app_amicale_admin');
        }

        return $this->render('amicale/offre_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/offre/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editOffre(
        OffreAmicale $offre,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(OffreAmicaleType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'تم تعديل العرض بنجاح.');
            return $this->redirectToRoute('app_amicale_admin');
        }

        return $this->render('amicale/offre_edit.html.twig', [
            'form' => $form->createView(),
            'offre' => $offre,
        ]);
    }

    #[Route('/offre/{id}', name: 'app_offre_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteOffre(
        OffreAmicale $offre,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $em->remove($offre);
            $em->flush();
            $this->addFlash('success', 'تم حذف العرض بنجاح.');
        }

        return $this->redirectToRoute('app_amicale_admin');
    }

    #[Route('/demande/{id}', name: 'app_demande_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteDemande(
        DemandeAmicale $demande,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $demande->getId(), $request->request->get('_token'))) {
            $em->remove($demande);
            $em->flush();
            $this->addFlash('success', 'تم حذف الطلب بنجاح.');
        }

        return $this->redirectToRoute('app_amicale_admin');
    }
}
