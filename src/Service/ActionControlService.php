<?php

namespace App\Service;

use App\Repository\ActionControleRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ActionControlService
{
    public function __construct(
        private ActionControleRepository $repo,
        private Security $security
    ) {}

    // src/Service/ActionControlService.php

public function canShow(string $action): bool
{
    $user = $this->security->getUser();
    if (!$user) {
        return false;
    }

    // Récupérer les rôles de l'utilisateur
    $userRoles = $user->getRoles();

    foreach ($userRoles as $role) {
        $control = $this->repo->findByRoleAndAction($role, $action);

        if ($control) {
            // Appliquer la logique de visibilité
            if (!$control->isActive()) {
                return false;
            }
            if ($control->isMasque()) {
                return false;
            }
            return true; // Trouvé et autorisé
        }
    }

    return true; // Par défaut, visible
}
}