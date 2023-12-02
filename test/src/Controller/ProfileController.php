<?php

namespace App\Controller;

use App\Service\PaymentApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    // #[Route('/profile', name: 'app_profile')]
    // public function index(): Response
    // {
    //     return $this->render('profile/index.html.twig', [
    //         'controller_name' => 'ProfileController',
    //     ]);
    // }

    #[Route('/update-card', name: 'update-card')]
    public function index(PaymentApiService $paymentApiService, Request $request): Response
    {
        if( ($request->request->get('card_number')) && ($request->request->get('cvv'))){
            
            $paymentApiService->updateUser(
                $request->request->get('cvv'),
                $request->request->get('card_number'),
                // getting the value of user connected
                $this->getUser()->getId(),
            );
            
        }

        return $this->render('profile/updateCard.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


}
