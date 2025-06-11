<?php

// src/Entity/Visite.php
namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 100)]
    private string $pays;

    #[ORM\Column(length: 255)]
    private string $lieu;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $heureDebut;

    #[ORM\Column(type: 'integer')]
    private int $duree; // en heures

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $heureFin;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(targetEntity: Guide::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(nullable: false)]
    private Guide $guide;

    #[ORM\OneToMany(mappedBy: 'visite', targetEntity: Visiteur::class, cascade: ['persist', 'remove'])]
    private Collection $visiteurs;

    public function __construct()
    {
        $this->visiteurs = new ArrayCollection();
    }

    // ... getters/setters

    public function getHeureFin(): \DateTimeInterface
    {
        return (clone $this->heureDebut)->modify("+{$this->duree} hours");
    }
}
