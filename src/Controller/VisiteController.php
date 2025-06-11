<?php

namespace App\Controller;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/visite')]
class VisiteController extends AbstractController
{
    #[Route('/', name: 'visite_index', methods: ['GET', 'POST'])]
    public function index(Request $request, VisiteRepository $visiteRepository, EntityManagerInterface $em): Response
    {
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($visite);
            $em->flush();
            return $this->redirectToRoute('visite_index');
        }

        return $this->render('visite/index.html.twig', [
            'visites' => $visiteRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'visite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($visite);
            $em->flush();

            return $this->redirectToRoute('visite_index');
        }

        return $this->render('visite/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'visite_show', methods: ['GET'])]
    public function show(Visite $visite): Response
    {
        return $this->render('visite/show.html.twig', [
            'visite' => $visite,
        ]);
    }

    #[Route('/{id}/edit', name: 'visite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visite $visite, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('visite_index');
        }

        return $this->render('visite/edit.html.twig', [
            'form' => $form->createView(),
            'visite' => $visite,
        ]);
    }

    #[Route('/{id}', name: 'visite_delete', methods: ['POST'])]
    public function delete(Request $request, Visite $visite, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visite->getId(), $request->request->get('_token'))) {
            $em->remove($visite);
            $em->flush();
        }

        return $this->redirectToRoute('visite_index');
    }
}
