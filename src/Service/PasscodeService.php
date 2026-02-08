<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasscodeService
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
    ) {}

 public function generateAndSendPasscode(User $user): void
{
    //dd($user);
    // Génère un code aléatoire
    $passcode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

    // Définit l'expiration (5 minutes)
    $expiresAt = new \DateTimeImmutable('+5 minutes');

    // Enregistre le code dans l'utilisateur
    $user->setPasscode($passcode);
    $user->setPasscodeExpiresAt($expiresAt);
    $user->setPasscodeVerified(false);

    $this->em->flush();

    // Envoie l'email
    $email = (new Email())
        ->from('benamarabadr@gmail.com')
        ->to($user->getEmail())
        ->subject('Votre code de vérification')
        ->html("
            <h3>Votre code de vérification est :</h3>
            <h1><strong>$passcode</strong></h1>
            <p>Il expire dans 5 minutes.</p>
        ");

    

    try {
        $this->mailer->send($email);
    } catch (\Exception $e) {
    }
}

    public function verifyPasscode(User $user, string $passcode): bool
    {
        // Vérifie l'expiration
        if ($user->getPasscodeExpiresAt() < new \DateTimeImmutable()) {
            return false;
        }

        // Vérifie le code
        if ($user->getPasscode() !== $passcode) {
            return false;
        }

        // Marque comme vérifié
        $user->setPasscodeVerified(true);
        $this->em->flush();

        return true;
    }
    public function saveUser(User $user): void
{
    $this->em->flush(); // Sauvegarde l'utilisateur
}
}
