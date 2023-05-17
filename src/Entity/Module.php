<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\StatusEnum;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $mesure = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column]
    private ?int $nbdata = null;

    #[ORM\Column]
    /**
     * @ORM\Column(type="string", length=50)
     * @Enum(class=StatusEnum::class)
     */
    private ?string $etat = null;

    
    #[ORM\Column(type:"blob")]
    private $image;

    public function __construct(string $description,string $nom,float $mesure,int $duree, int $nbdata , string $etat ,$image)
    {
        $this->description = $description;
        $this->nom = $nom;
        $this->mesure = $mesure;
        $this->duree = $duree;
        $this->nbdata = $nbdata;
        $this->etat = $etat;
        $this->image= $image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMesure(): ?float
    {
        return $this->mesure;
    }

    public function setMesure(?float $mesure): self
    {
        $this->mesure = $mesure;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getNbdata(): ?int
    {
        return $this->nbdata;
    }

    public function setNbdata(int $nbdata): self
    {
        $this->nbdata = $nbdata;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
    
}

