<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Entity\Visite;
use App\Form\VisiteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/visiteur')]
class VisiteurController extends AbstractController
{
    #[Route('/new/{id}', name: 'visiteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Visite $visite, EntityManagerInterface $em): Response
    {
        if (count($visite->getVisiteurs()) >= 18) {
            $this->addFlash('error', 'Nombre maximum de visiteurs atteint pour cette visite.');
            return $this->redirectToRoute('visite_show', ['id' => $visite->getId()]);
        }

        $visiteur = new Visiteur();
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $visiteur->setVisite($visite);
            $em->persist($visiteur);
            $em->flush();

            return $this->redirectToRoute('visite_show', ['id' => $visite->getId()]);
        }

        return $this->render('visiteur/new.html.twig', [
            'form' => $form->createView(),
            'visite' => $visite,
        ]);
    }
}
