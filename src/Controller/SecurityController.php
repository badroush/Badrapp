<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\PasscodeFormType;
use App\Service\PasscodeService;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    #[Route('/verify-passcode', name: 'app_verify_passcode', methods: ['GET', 'POST'])]
    public function verifyPasscode(
        Request $request,
        EntityManagerInterface $em,
        PasscodeService $passcodeService
    ): Response {
        // Récupère l'utilisateur depuis la session
        $session = $request->getSession();
        $userId = $session->get('user_id');
 //dd("sc = ".$userId);
        $user = $em->find(User::class, $userId);

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PasscodeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passcode = $form->get('passcode')->getData();

            if ($passcodeService->verifyPasscode($user, $passcode)) {
                // Connexion réussie
                $session->set('is_passcode_verified', true);
                return $this->redirectToRoute('app_home'); // Remplace par ta route
            }

            $this->addFlash('error', 'Code invalide ou expiré.');
        }

        return $this->render('security/verify_passcode.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
=======
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityController extends AbstractController
{
    #[Route('/access-denied', name: 'access_denied')]
    public function accessDenied(Request $request): Response
    {
        // Ajouter un message flash pour afficher avec SweetAlert
        $this->addFlash('error', 'Accès refusé. Vous n\'avez pas les droits nécessaires.');

        // Rediriger vers la page précédente
        $referer = $request->headers->get('referer');
        $route = $this->generateUrl('app_home'); // Fallback

        if ($referer && $referer !== $request->getUri()) {
            $route = $referer;
        }

        return $this->redirect($route);
    }
}
>>>>>>> d6476d60ab6c137fd57e0e94d3b5e16948081daa
