<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/api/users', name: 'create_user', methods: ['POST'])]
    public function createUser(): JsonResponse
    {
        return $this->json([
            'user' => '',
            'msg' => 'Utilisateur crée!'
        ]);
    }

    #[Route('/api/users', name: 'list_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        return $this->json([
            'users' => [],
            'msg' => 'Liste des utilisateurs!'
        ]);
    }
}
