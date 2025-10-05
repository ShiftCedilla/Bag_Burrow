<?php

namespace App\Controller;

use App\Entity\Bag;
use App\Entity\Status;
use App\Entity\User;
use App\Form\BagType;
use App\Repository\BagRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/bag')]
final class BagController extends AbstractController
{
    #[Route(name: 'app_bag_index', methods: ['GET'])]
    public function index(BagRepository $bagRepository, StatusRepository $statusRepository)
    {
        $status = $statusRepository ->avaibleBag();
        return $this->render('bag/index.html.twig', [
            'bags' => $bagRepository->findBy(
                ['status'=> $status]
            )
        ]);
    }

    #[Route('/new', name: 'app_bag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user)
    {   

        $bag = new Bag();
        $form = $this->createForm(BagType::class, $bag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $file = $form->get('img')->getData(); 
            if($file) {
             
                $newFileName = time() . '-' . $file->getClientOriginalName(); // cette ligne permet de changer le nom du fichier de manière unique 
                // time() c'est comme unique id 
                $file->move($this->getParameter('bag_dir'), $newFileName); 
                $bag->setImg($newFileName);
            }

            $bag->setOwner($user);
            $entityManager->persist($bag);
            $entityManager->flush();

            return $this->redirectToRoute('app_bag_index');
        }

        return $this->render('bag/new.html.twig', [
            'bag' => $bag,
            'form' => $form,
            ]); 
        
    }
   

    #[Route('/{id}', name: 'app_bag_show', methods: ['GET'])]
    public function show(Bag $bag, StatusRepository $statusRepository)
    {   $status = $statusRepository ->avaibleBag();
        return $this->render('bag/show.html.twig', [
            'bag' => $bag,
            'status'=> $status
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bag $bag, EntityManagerInterface $entityManager, UserInterface $user)
    {   
        // condition pour que seul le preopriétaire du sac puisse le modifier
        if ($bag->getOwner() !== $user) {
        $this-> addFlash('error' , 'Vous ne pouvez pas modifier un sac qui ne vous appartient pas');
        return $this-> redirectToRoute('app_bag_index');
        }

        $form = $this->createForm(BagType::class, $bag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('img')->getData(); 
            if($file) {
             
                $newFileName = time() . '-' . $file->getClientOriginalName(); // cette ligne permet de changer le nom du fichier de manière unique 
                // time() c'est comme unique id 
         
                $file->move($this->getParameter('bag_dir'), $newFileName); 

                $bag->setImg($newFileName);
              
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_bag_index');
        }

        return $this->render('bag/edit.html.twig', [
            'bag' => $bag,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_bag_delete', methods: ['POST'])]
    public function delete(Request $request, Bag $bag, EntityManagerInterface $entityManager, UserInterface $user)
    { 
        //condition pour que seul le preopriétaire du sac puisse le supprimer
        if ($bag->getOwner() !== $user) {
        $this->addFlash('error','Vous ne pouvez pas supprimer un sac qui ne vous appartient pas.');
        return $this-> redirectToRoute('app_bag_index');
        }

        if ($this->isCsrfTokenValid('delete'.$bag->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bag_index');
    }

/////Demande d'emprunt d'un sac/////////////////////////////
 
    #[Route('/{id}/borrow_request', name: 'app_bag_request')]
    public function borrowRequest(Bag $bag, StatusRepository $statusRepository, EntityManagerInterface $em, UserInterface $user)
{  
     //empécher le propréiétaire de pouvoir emprunter son sac
    if($bag->getOwner() === $user) {
        $this-> addFlash('error' , 'Vous ne pouvez pas emprunté votre propre sac');
        return $this-> redirectToRoute('app_bag_index');
    }

    //si le sac est disponible alors modification du status name en 'demandé' et le demandeur devient le borrower provisoirement
    if ($bag->getStatus()->getName() === 'disponible') {
        
        $statusDemande = $statusRepository -> DemandeBag();
        $bag->setBorrower($user);
        $bag->setStatus($statusDemande);
        $em->flush();
        return $this->redirectToRoute('app_bag_index');
        }
     return $this->redirectToRoute('app_bag_index');
}
/////////traitement de la demande par l'owner du sac/////////

#[Route('/{id}/accept_borrow', name: 'app_borrow_accept')]
public function acceptBorrow(Bag $bag, EntityManagerInterface $em, StatusRepository $statusRepository, UserInterface $user)
{
    //condition pour que seul le preopriétaire du sac puisse accepter une demande d'emprunt
    if ($bag->getOwner() !== $user) {
    $this->addFlash('error','Vous ne pouvez pas accepter une demande pour un sac qui ne vous appartient pas.');
    return $this->redirectToRoute('app_bag_index');
    }

    $bag->setStatus($statusRepository -> NotAvaibleBag());
    $em->flush();
    return $this->redirectToRoute('app_user'); 
}

#[Route('/{id}/refuse_borrow', name: 'app_borrow_refuse')]
public function refuseBorrow(Bag $bag, EntityManagerInterface $em, StatusRepository $statusRepository , UserInterface $user)
{   
    //condition pour que seul le preopriétaire du sac puisse refuser une demande d'emprunt
    if ($bag->getOwner() !== $user) {
    $this->addFlash('error', 'Vous ne pouvez pas refuser une demande pour un sac qui ne vous appartient pas.');
    return $this->redirectToRoute('app_bag_index');
    }

    $bag->setStatus($statusRepository -> AvaibleBag());
    $em->flush();
    return $this->redirectToRoute('app_user'); 
}

#[Route('/{id}/return_borrow', name: 'app_borrow_return')]
public function returnBorrow(Bag $bag, EntityManagerInterface $em, StatusRepository $statusRepository, UserInterface $user)
{
    //condition pour que seul le preopriétaire du sac puisse retourner un emprunt
    if ($bag->getOwner() !== $user) {
    $this->addFlash('error', 'Vous ne pouvez pas retourner un emprunt pour un sac qui ne vous appartient pas.');
    return $this->redirectToRoute('app_bag_index');
     }

    $bag->setBorrower(null);
    $bag->setStatus($statusRepository -> AvaibleBag());
    $em->flush();

    return $this->redirectToRoute('app_user');
}
}
