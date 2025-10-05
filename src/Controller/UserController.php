<?php

namespace App\Controller;

use App\Repository\BagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(BagRepository $bagRepository)
    {   
        $user = $this->getUser(); 

        // Sacs publiés par l'utilisateur
        $bags = $bagRepository->findBy(['owner' => $user->getId()]);

        //sac demandé par un autre utilisateur
         $requestedBags = $bagRepository->findRequestedByOwner($user);

        // Sacs empruntés par l'utilisateur
        $borrowedBags = $bagRepository->findBy(['borrower' => $user->getId()]);
        
        
        
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'bags' => $bags,
            'requestedBags'=> $requestedBags,
            'borrowedBags' => $borrowedBags
            
        
        ]);
    }
}