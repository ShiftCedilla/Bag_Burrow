<?php

namespace App\Controller;

use App\Entity\Condition;
use App\Form\ConditionType;
use App\Repository\ConditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/condition')]
final class ConditionController extends AbstractController
{
    #[Route(name: 'app_condition_index', methods: ['GET'])]
    public function index(ConditionRepository $conditionRepository): Response
    {
        return $this->render('condition/index.html.twig', [
            'conditions' => $conditionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_condition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $condition = new Condition();
        $form = $this->createForm(ConditionType::class, $condition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($condition);
            $entityManager->flush();

            return $this->redirectToRoute('app_condition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('condition/new.html.twig', [
            'condition' => $condition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condition_show', methods: ['GET'])]
    public function show(Condition $condition): Response
    {
        return $this->render('condition/show.html.twig', [
            'condition' => $condition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_condition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Condition $condition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConditionType::class, $condition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_condition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('condition/edit.html.twig', [
            'condition' => $condition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condition_delete', methods: ['POST'])]
    public function delete(Request $request, Condition $condition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$condition->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($condition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_condition_index', [], Response::HTTP_SEE_OTHER);
    }
}
