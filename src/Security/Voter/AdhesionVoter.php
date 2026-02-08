<?php

namespace App\Security\Voter;

use App\Entity\Adhesion;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdhesionVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Adhesion) {
            return false;
        }

        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
    $user = $token->getUser();

    if (!$user instanceof User) {
        return false;
    }

    /** @var Adhesion $adhesion */
    $adhesion = $subject;

    // Les admins peuvent tout faire
    if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
        return true;
    }

    // L'utilisateur doit appartenir au même établissement que l'adhésion
    $hasSameEtablissement = $user->getEtablissement()
        && $adhesion->getEtablissement()
        && $user->getEtablissement()->getId() === $adhesion->getEtablissement()->getId();

    // Empêcher modification/suppression si la fiche est validée
    if ($adhesion->getValider()) {
        return false;
    }

    switch ($attribute) {
        case self::VIEW:
            return $hasSameEtablissement;
        case self::EDIT:
        case self::DELETE:
            return $hasSameEtablissement;
    }

    return false;
}
}