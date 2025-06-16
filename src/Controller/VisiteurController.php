<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Entity\Visite;
use App\Form\VisiteurType;
use App\Repository\VisiteRepository;
use App\Repository\VisiteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/visiteur')]
class VisiteurController extends AbstractController
{
    #[Route('/', name: 'visiteur_index', methods: ['GET'])]
    public function index(VisiteurRepository $visiteurRepository): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'visiteurs' => $visiteurRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'visiteur_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, VisiteRepository $visiteRepository, EntityManagerInterface $em): Response
    {
        $visite = $visiteRepository->find($id);
        if (!$visite) {
            throw $this->createNotFoundException('Visite non trouvée.');
        }

        if (count($visite->getVisiteurs()) >= 18) {
            $this->addFlash('danger', 'Nombre maximum de visiteurs atteint pour cette visite.');
            return $this->redirectToRoute('visite_show', ['id' => $visite->getId()]);
        }

        $visiteur = new Visiteur();
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visiteur->setVisite($visite);
            $em->persist($visiteur);
            $em->flush();

            $this->addFlash('success', 'Visiteur ajouté avec succès.');
            return $this->redirectToRoute('visite_show', ['id' => $visite->getId()]);
        }

        return $this->render('visiteur/new.html.twig', [
            'form' => $form->createView(),
            'visite' => $visite,
        ]);
    }

    #[Route('/choisir-visite', name: 'visiteur_new_selection', methods: ['GET'])]
    public function selectVisite(VisiteRepository $visiteRepository): Response
    {
        return $this->render('visiteur/select_visite.html.twig', [
            'visites' => $visiteRepository->findAll(),
        ]);
    }

    #[Route('/choisir-visite/redirection', name: 'visiteur_new_selection_redirect', methods: ['GET'])]
    public function redirectToNew(Request $request): Response
    {
        $id = $request->query->get('id');

        if (!$id) {
            $this->addFlash('danger', 'Aucune visite sélectionnée.');
            return $this->redirectToRoute('visiteur_new_selection');
        }

        return $this->redirectToRoute('visiteur_new', ['id' => $id]);
    }

    #[Route('/{id}/show', name: 'visiteur_show', methods: ['GET'])]
    public function show(Visiteur $visiteur): Response
    {
        return $this->render('visiteur/show.html.twig', [
            'visiteur' => $visiteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'visiteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visiteur $visiteur, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le visiteur a été mis à jour avec succès.');
            return $this->redirectToRoute('visiteur_index');
        }

        return $this->render('visiteur/edit.html.twig', [
            'form' => $form->createView(),
            'visiteur' => $visiteur,
        ]);
    }

    #[Route('/{id}', name: 'visiteur_delete', methods: ['POST'])]
    public function delete(Request $request, Visiteur $visiteur, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $visiteur->getId(), $request->request->get('_token'))) {
            $em->remove($visiteur);
            $em->flush();
            $this->addFlash('success', 'Le visiteur a été supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Échec de la vérification CSRF. Suppression annulée.');
        }

        return $this->redirectToRoute('visiteur_index');
    }
}
