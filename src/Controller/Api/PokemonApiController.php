<?php

namespace App\Controller\Api;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/pokemon')]
#[IsGranted('ROLE_USER')]
class PokemonApiController extends AbstractController
{
    #[Route('/my-collection', name: 'api_pokemon_my_collection', methods: ['GET'])]
    public function getMyCollection(PokemonRepository $pokemonRepository): JsonResponse
    {
        $user = $this->getUser();
        $pokemons = $pokemonRepository->getUserPokemons($user);
        
        $pokemonData = array_map(function($pokemon) {
            return [
                'id' => $pokemon->getId(),
                'pokedex' => $pokemon->getPokedex()->getId(),
                'level' => $pokemon->getLevel(),
                'power' => $pokemon->getPower(),
                'isAlive' => $pokemon->isAlive(),
            ];
        }, $pokemons);

        return $this->json([
            'success' => true,
            'data' => $pokemonData
        ]);
    }
}