<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PaymentApiService;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'app_subscription')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // getting all subscription details from data base
        $subscriptions = $entityManager->getRepository(Subscription::class)->findAll();
        // dd($subscriptions);

        // if  user has already one subscription then  show his subscription instead  of form
        $hasSubscription = (!empty($this->getUser()->getTransactions()->getValues()));
        $mySubscription = $this->getUser()->getTransactions()->getValues();

        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
            'subscriptions' => $subscriptions,
            'hasSubscription' => $hasSubscription,
            'mySubscription' => $mySubscription, 
        ]);
    }


    #[Route('/payment', name: 'subscription_selection')]
    public function subscriptionSelection(EntityManagerInterface $em, PaymentApiService $paymentApiService, Request $request): Response
    {

        // checking is user is already created in API
        $userCreated = $paymentApiService->getUser($this->getUser()->getId());
        // dd(empty($userCreated) );

        // If user not already created in api we can create now
        if(empty($userCreated)){
            // Utilisation du service pour créer un utilisateur
            $result = $paymentApiService->createUser(
                $request->request->get('cvv'),
                $request->request->get('card_number'),
                // getting the value of user connected
                $this->getUser()->getId(),
            );
            
            $subscription = $em->getRepository(Subscription::class)->findOneById($request->request->get("subscription"));
            


            // Creating transaction on api
            $paymentApiService->createTransaction(
                // getting the value of user connected
                $this->getUser()->getId(),
                $subscription->getPrice()
            );

            
            
            //also saving in database this transaction
            $transaction = new Transaction();
            $transaction->setUser($this->getUser());
            
            // for the moment using default values
            $transaction->setSubscription($subscription);
            $transaction->setDate(new \DateTime());

            // saving in  database 
            $em->persist($transaction);
            $em->flush();
            
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_subscription');
        
    }
}
