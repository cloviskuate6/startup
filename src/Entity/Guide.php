<?php
// src/Entity/Guide.php
namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuideRepository::class)]
class Guide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 50)]
    private string $statut; // 'actif' ou 'inactif'

    #[ORM\Column(length: 100)]
    private string $pays;

    #[ORM\OneToMany(mappedBy: 'guide', targetEntity: Visite::class)]
    private Collection $visites;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
    }

    // ... getters et setters (nom, prenom, statut, etc.)
}
