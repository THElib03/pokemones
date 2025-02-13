<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokemon')]
final class PokemonController extends AbstractController
{
    #[Route('/new', name: 'app_pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
        $pokemon = new Pokemon();
        $pokemon->setIsAlive(true); 
        $pokemon->setLevel(1); 
        $pokemon->setPower(10); 
    
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokemon);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_pokemon_colection', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('pokemon/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route("/colection", name: 'app_pokemon_colection', methods: ['GET'])]
    public function colection(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokemon/colection.html.twig', [
            'pokemons' => $pokemonRepository->getUserPokemons($this->getUser()),
        ]);
    }

    #[Route('/{id}', name: 'app_pokemon_show', methods: ['GET'])]
    public function show(Pokemon $pokemon): Response
    {
        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokemon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_colection', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/edit.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/train', name: 'app_pokemon_train', methods: ['GET', 'POST'])]
    public function train(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {

        $pokemon->setPower($pokemon->getPower() + 10);

        $entityManager->persist($pokemon);
        $entityManager->flush();
        return $this->redirectToRoute('app_pokemon_colection', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/evolve', name: 'app_pokemon_evolve', methods: ['GET', 'POST'])]
    public function evolve(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        if ($pokemon->getPokedex()->getEvolution() != null && $pokemon->getLevel() >= $pokemon->getPokedex()->getEvolutionLevel()) {

            $pokemon->setPokedex($pokemon->getPokedex()->getEvolution());
            $entityManager->persist($pokemon);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_pokemon_colection', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_pokemon_delete', methods: ['POST'])]
    public function delete(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pokemon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokemon_colection', [], Response::HTTP_SEE_OTHER);
    }
}
