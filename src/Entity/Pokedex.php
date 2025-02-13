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
    private ?int $evolutionLevel = null;

    #[ORM\OneToOne(targetEntity: Pokedex::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Pokedex $evolution = null;

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
        return $this->evolutionLevel;
    }

    public function setEvolutionLevel(?int $evolutionLevel): static
    {
        $this->evolutionLevel = $evolutionLevel;

        return $this;
    }

    public function getEvolution(): ?self
    {
        return $this->evolution;
    }

    public function setEvolution(?self $evolution): static
    {
        $this->evolution = $evolution;

        return $this;
    }
}
