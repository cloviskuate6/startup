<?php

// src/Entity/Visiteur.php
namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(type: 'boolean')]
    private bool $present = false;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(targetEntity: Visite::class, inversedBy: 'visiteurs')]
    #[ORM\JoinColumn(nullable: false)]
    private Visite $visite;

    // ... getters et setters
}
