<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security; // ✅ Correctuse 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_show', methods: ['GET'])]
    public function show(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

#[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
public function edit(
    Request $request, 
    EntityManagerInterface $em, 
    Security $security,
    Filesystem $filesystem // 👈 Ajoute cette ligne
): Response {
    $user = $security->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException();
    }

    $form = $this->createForm(ProfileFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de l'avatar
        $avatarFile = $form->get('avatar')->getData();

        if ($avatarFile) {
            // Supprime l'ancienne image si elle existe
            if ($user->getAvatar()) {
                $oldImagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/avatars/' . $user->getAvatar();
                if (file_exists($oldImagePath)) {
                    $filesystem->remove($oldImagePath);
                }
            }
            // Génère un nom unique pour la nouvelle image
            $newFilename = uniqid() . '.' . $avatarFile->guessExtension();

            // Déplace le fichier vers le répertoire uploads/avatars
            $avatarFile->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads/avatars',
                $newFilename
            );
            // Enregistre le nom du fichier dans l'entité
            $user->setAvatar($newFilename);
        }

        $em->flush();
        $this->addFlash('success', 'Profil mis à jour avec succès.');

        return $this->redirectToRoute('app_profile_show');
    }

    return $this->render('profile/edit.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
    ]);
}

    #[Route('/change-password', name: 'app_profile_change_password', methods: ['GET', 'POST'])]
public function changePassword(
    Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher,
    Security $security
): Response {
    $user = $security->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException('Accès refusé.');
    }

    $form = $this->createForm(ChangePasswordFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newPassword = $form->get('plainPassword')->getData();

        // Hash et mise à jour du mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        $em->flush();

        $this->addFlash('success', 'Mot de passe mis à jour avec succès.');

        return $this->redirectToRoute('app_profile_show');
    }

    return $this->render('profile/change_password.html.twig', [
        'form' => $form->createView(),
    ]);
}
}