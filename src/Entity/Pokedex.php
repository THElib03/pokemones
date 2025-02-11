<?php

namespace App\Entity;

use App\Repository\PokedexRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokedexRepository::class)]
class Pokedex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $type = [];

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $EvolutionLevel = null;

    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'getPreEvolution', cascade: ['persist', 'remove'])]
    private ?self $Evolution = null;

       public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getEvolutionLevel(): ?int
    {
        return $this->EvolutionLevel;
    }

    public function setEvolutionLevel(?int $EvolutionLevel): static
    {
        $this->EvolutionLevel = $EvolutionLevel;

        return $this;
    }

    public function getEvolution(): ?self
    {
        return $this->Evolution;
    }

    public function setEvolution(?self $Evolution): static
    {
        $this->Evolution = $Evolution;

        return $this;
    }





}
