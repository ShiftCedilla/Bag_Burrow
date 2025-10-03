<?php

namespace App\Controller;

use App\Entity\Bag;
use App\Entity\User;
use App\Form\BagType;
use App\Repository\BagRepository;
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
    public function index(BagRepository $bagRepository)
    {
        return $this->render('bag/index.html.twig', [
            'bags' => $bagRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager)
    {   
    //    vérification que le user est bien connecté (AbstractController)
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var \App\Entity\User $user */
             $user = $this->getUser();

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

            return $this->redirectToRoute('app_bag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bag/new.html.twig', [
            'bag' => $bag,
            'form' => $form,
            ]);
        
    }
   

    #[Route('/{id}', name: 'app_bag_show', methods: ['GET'])]
    public function show(Bag $bag): Response
    {
        return $this->render('bag/show.html.twig', [
            'bag' => $bag,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bag $bag, EntityManagerInterface $entityManager): Response
    {
        //vérification que le user est bien connecté (AbstractController)
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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

            return $this->redirectToRoute('app_bag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bag/edit.html.twig', [
            'bag' => $bag,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_bag_delete', methods: ['POST'])]
    public function delete(Request $request, Bag $bag, EntityManagerInterface $entityManager): Response
    {
        //vérification que le user est bien connecté (AbstractController)
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isCsrfTokenValid('delete'.$bag->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bag_index', [], Response::HTTP_SEE_OTHER);
    }
}
