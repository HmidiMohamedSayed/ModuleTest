<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRepository::class)]
class Historique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $moduleId = null;

    #[ORM\Column]
    private ?int $mesure = null;

    public function __construct($moduleId,$mesure)
    {
        $this->moduleId = $moduleId;
        $this->mesure = $mesure;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModuleId(): ?int
    {
        return $this->moduleId;
    }

    public function setModuleId(int $moduleId): self
    {
        $this->moduleId = $moduleId;

        return $this;
    }

    public function getMesure(): ?int
    {
        return $this->mesure;
    }

    public function setMesure(int $mesure): self
    {
        $this->mesure = $mesure;

        return $this;
    }
}
