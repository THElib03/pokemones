<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Pokemon;
use App\Form\BattleType;
use App\Form\HuntType;
use App\Repository\BattleRepository;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/battle')]
final class BattleController extends AbstractController
{
    #[Route(name: 'app_battle_index', methods: ['GET'])]
    public function index(BattleRepository $battleRepository): Response
    {
        return $this->render('battle/index.html.twig', [
            'battles' => $battleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_battle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $battle = new Battle();
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($battle);
            $entityManager->flush();

            return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('battle/new.html.twig', [
            'battle' => $battle,
            'form' => $form,
        ]);
    }

    #[Route('/hunt', name: 'app_battle_hunt', methods: ['GET', 'POST'])]
    public function hunt(PokedexRepository $pokeRepo, Request $request, EntityManagerInterface $entityManager): Response {
        $pkdx = $pokeRepo -> generateWildPokemon();
        $form = $this -> createForm(HuntType::class, null, ['method' => 'POST']);

        if($request -> get('hunt') !== null){
            if(isset($request -> get('hunt')['flee'])){
                $this -> addFlash('warning', 'Has huido como un cobarde del ' . $pkdx -> getName() . '.');
                return $this->redirectToRoute('app_main');




            }
            elseif(isset($request -> get('hunt')['hunt'])){
                if(rand(0, 9) > 5){
                    $pkmn = new Pokemon();
                    $pkmn -> setPokedex($pkdx);
                    $pkmn -> setuser($this -> getUser());
                    $pkmn -> setLevel(1);
                    $pkmn -> setPower(10);
                    $pkmn -> setIsAlive(true);

                    $entityManager -> persist($pkmn);
                    $entityManager -> flush();

                    $this -> addFlash('warning', '¡Bravo! Has esclavizado un nuevo ' . $pkdx -> getName() . '<br>puedes revisarlo en tu coleción.');
                }
                else{
                    $this -> addFlash('warning', '¡Vaya! ' . $pkdx -> getName() . ' se ha resistido a tus encantos.');
                }
            }
        }

        return $this -> render('battle/hunt.html.twig', [
            'pokedex' => $pkdx, 'form' => $form
        ]);
    }   

    #[Route('/{id}', name: 'app_battle_show', methods: ['GET'])]
    public function show(Battle $battle): Response
    {
        return $this->render('battle/show.html.twig', [
            'battle' => $battle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_battle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Battle $battle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('battle/edit.html.twig', [
            'battle' => $battle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_battle_delete', methods: ['POST'])]
    public function delete(Request $request, Battle $battle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$battle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($battle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_battle_index', [], Response::HTTP_SEE_OTHER);
    }
}

