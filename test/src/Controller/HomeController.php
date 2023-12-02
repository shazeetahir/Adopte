<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render("home/index.html.twig");
    }

    #[Route('/all-users', name: 'all-users')]
    public function allUsers(EntityManagerInterface $entityManager): Response
    {
        // Get all users
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render("home/listUsers.html.twig", [
            'users' => $users,
        ]);
    }

    

}
