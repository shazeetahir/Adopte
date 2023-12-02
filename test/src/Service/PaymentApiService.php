<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaymentApiService
{
    private $httpClient;
    private $server;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->server = "http://adopteundev.adopteunmec.com:3042";
    }


    public function createUser($cvv, $cardNumber, $userId)
    {
        // Appel à l'API pour créer un utilisateur
        $response = $this->httpClient->request('POST', $this->server.'/user', [
            'json' => [
                'cvv' => $cvv,
                'card_number' => $cardNumber,
                'user_id' => $userId,
            ],
        ]);

        // Gérer la réponse, par exemple, retourner le contenu JSON
        // we dont need this to return anything 
        return $response->toArray();
    }


    public function getUser($userId)
    {
        // Appel à l'API pour créer un utilisateur
        $response = $this->httpClient->request('GET', $this->server.'/user/'.$userId);

        // Gérer la réponse, par exemple, retourner le contenu JSON
        // we dont need this to return anything 
        return $response->toArray();
    }



    // Modification user card
    public function updateUser($cvv, $cardNumber, $userId)
    {
        // Call the API to update user information
        $response = $this->httpClient->request('PUT', $this->server.'/user/' . $userId, [
            'json' => [
                'cvv' => $cvv,
                'card_number' => $cardNumber,
            ],
        ]);

        return $response->toArray(); 
    }


    public function createTransaction($userId, $amount)
    {
        // Call the API to create a transaction
        $response = $this->httpClient->request('POST', $this->server.'/transaction', [
            'json' => [
                'user_id' => $userId,
                'amount' => $amount,
            ],
        ]);

        return $response->toArray(); 
    }



}
