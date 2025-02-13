<?php

namespace App\Entity;

use App\Repository\BattleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BattleRepository::class)]
class Battle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user1 = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $pokemon1 = [];

    #[ORM\ManyToOne(inversedBy: 'battles')]
    private ?User $user2 = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $pokemon2 = null;

    /* Guide to state:
     * -1 - Battle created for PvE (battle not started, waiting for player to confirm)
     * 0 - Battle created for PvP & teams (battle not started, waiting for other player)
     * 1 - PvP battle assigned, waiting for second player to confirm.
     * 2 - Team battle assigned, waiting for players to confirm.
     * 3 - Battle (all types) is fought and state field is set.
     */
    #[ORM\Column]
    private ?int $state = null;

    /* Guide to result: 
     * null - Battle not done
     * any int - id of the winning user (0 in the case of wild winning)
     */
    #[ORM\Column(nullable: true)]
    private ?bool $result = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $confirm = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): static
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getPokemon1(): array
    {
        return $this->pokemon1;
    }

    public function setPokemon1(array $pokemon1): static
    {
        $this->pokemon1 = $pokemon1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->user2;
    }

    public function setUser2(?User $user2): static
    {
        $this->user2 = $user2;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isResult(): ?bool
    {
        return $this->result;
    }

    public function setResult(?bool $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getPokemon2(): ?array
    {
        return $this->pokemon2;
    }

    public function setPokemon2(?array $pokemon2): static
    {
        $this->pokemon2 = $pokemon2;

        return $this;
    }

    public function getConfirm(): array
    {
        return $this->confirm;
    }

    public function setConfirm(array $confirm): static
    {
        $this->confirm = $confirm;

        return $this;
    }
}
