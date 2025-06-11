<?php
// src/Controller/StatistiquesController.php
namespace App\Controller;

use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/stats')]
class StatistiquesController extends AbstractController
{
    #[Route('/', name: 'stats_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $conn = $em->getConnection();

        $visitesParMois = $conn->fetchAllAssociative("
            SELECT MONTH(date) AS mois, COUNT(*) AS total
            FROM visite
            GROUP BY mois
        ");

        $visitesParGuide = $conn->fetchAllAssociative("
            SELECT g.nom, g.prenom, COUNT(v.id) AS total
            FROM visite v
            JOIN guide g ON v.guide_id = g.id
            GROUP BY g.id
        ");

        $presenceParMois = $conn->fetchAllAssociative("
            SELECT MONTH(v.date) as mois,
                   COUNT(DISTINCT vs.id) as total_visiteurs,
                   SUM(vs.present = 1) as presents
            FROM visite v
            JOIN visiteur vs ON vs.visite_id = v.id
            GROUP BY mois
        ");

        return $this->render('admin/stats.html.twig', [
            'visitesParMois' => $visitesParMois,
            'visitesParGuide' => $visitesParGuide,
            'presenceParMois' => $presenceParMois,
        ]);
    }
}
