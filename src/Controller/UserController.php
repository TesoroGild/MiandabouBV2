<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UploadService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{
    #[Route('/api/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager, 
        LoggerInterface $logger, UploadService $uploadService,
        UserPasswordHasherInterface $passwordHasher, UsersRepository $usersRepository): JsonResponse
    {
        $user = new Users();
        $user->setUsername($request->request->get('username'));
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $email = $request->request->get('email');
        $user->setEmail($email);
        $user->setNumber($request->request->get('number'));
        $user->setRoles($request->request->all('roles'));
        $user->setIsActive($request->request->get('status'));
        $dateString = $request->request->get('dateOfBirth');
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        $user->setDateOfBirth($date);
        $file = $request->files->get('picture');

        if ($file) {
            $uploadInfo = $uploadService->handleImageUpload($file);
            $user->setPicture($uploadInfo['filename']);
            $user->setContenthash($uploadInfo['hash']);
        } else {
            $user->setPicture(null);
            $user->setContenthash(null);
        }

        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $request->request->get('password')
            )
        );

        $timestamp = new \DateTime();
        $user->setTimecreated($timestamp);
        $user->setTimemodified($timestamp);

        //Ecriture en BDD
        $entityManager->persist($user);
        $entityManager->flush();

        $userAdded = $usersRepository->findUser($email);

        return $this->json([
            'user' => $userAdded,
            'msg' => 'Utilisateur ajouté!'
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/users', name: 'list_users', methods: ['GET'])]
    public function getUsers(UsersRepository $usersRepository, LoggerInterface $logger): JsonResponse
    {
        try {
            $users = $usersRepository->findAll();
            
            return $this->json([
                'users' => $users,
                'msg' => 'Liste des utilisateurs!'
            ], Response::HTTP_OK);
        } catch (\Throwable $t) {//\Exception $e
            $logger->error('Erreur getusers: ' . $t->getMessage());
            return $this->json(
                ['msg' => $t->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
