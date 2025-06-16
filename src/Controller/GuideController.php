<?php

namespace App\Controller;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Repository\GuideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class GuideController extends AbstractController
{
    public function index(GuideRepository $guideRepository): Response
    {
        return $this->render('guide/index.html.twig', [
            'guides' => $guideRepository->findAll(),
        ]);
    }

    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $guide->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', "Erreur lors de l'enregistrement de la photo.");
                }
            }

            $em->persist($guide);
            $em->flush();

            $this->addFlash('success', 'Le guide a été ajouté avec succès.');
            return $this->redirectToRoute('guide_index');
        }

        return $this->render('guide/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(int $id, GuideRepository $guideRepository): Response
    {
        $guide = $guideRepository->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide non trouvé.');
        }

        return $this->render('guide/show.html.twig', [
            'guide' => $guide,
        ]);
    }

    public function edit(int $id, Request $request, GuideRepository $guideRepository, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $guide = $guideRepository->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide non trouvé.');
        }

        $form = $this->createForm(GuideType::class, $guide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $guide->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', "Erreur lors de l'enregistrement de la nouvelle photo.");
                }
            }

            $em->flush();
            $this->addFlash('success', 'Le guide a été modifié avec succès.');
            return $this->redirectToRoute('guide_index');
        }

        return $this->render('guide/edit.html.twig', [
            'form' => $form->createView(),
            'guide' => $guide,
        ]);
    }

    public function confirmDelete(int $id, GuideRepository $guideRepository): Response
    {
        $guide = $guideRepository->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide non trouvé.');
        }

        return $this->render('guide/delete.html.twig', [
            'guide' => $guide,
        ]);
    }

    public function delete(Request $request, int $id, GuideRepository $guideRepository, EntityManagerInterface $em): Response
    {
        $guide = $guideRepository->find($id);
        if (!$guide) {
            throw $this->createNotFoundException('Guide non trouvé.');
        }

        if ($this->isCsrfTokenValid('delete' . $guide->getId(), $request->request->get('_token'))) {
            $em->remove($guide);
            $em->flush();
            $this->addFlash('success', 'Le guide a été supprimé avec succès.');
        }

        return $this->redirectToRoute('guide_index');
    }
}
