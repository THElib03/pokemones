<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Pokemon;
use App\Form\BattleType;
use App\Form\HuntType;
use App\Repository\BattleRepository;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
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

    #[Route('/newWild/{id}', name: 'app_battle_new_wild', methods: ['GET'])]
    public function newWild(int $id, PokedexRepository $pkdxRepo, PokemonRepository $pkmnRepo, Request $request, EntityManagerInterface $entMngr): Response{
        $battle = new Battle();
        $pkmn = $pkmnRepo -> getPokemonById($id)[0];

        $wildPkdx = $pkdxRepo -> generateWildPokemon();
        $wild = new Pokemon();
        $wild -> setPokedex($wildPkdx);
        $wild -> setuser(null);
        $wild -> setLevel(rand($pkmn -> getLevel() - 3, $pkmn -> getLevel() + 3));
        $wild -> setPower(rand($pkmn -> getPower() - 15, $pkmn -> getPower() + 15));
        $wild -> setIsAlive(true);

        $battle -> setUser1($this -> getUser());
        $battle -> setPokemon1([$pkmn]);
        $battle -> setPokemon2([$wild]);
        $battle -> setState(-1);
        $battle -> setConfirm([false, true]);

        $entMngr -> persist($wild);
        $entMngr -> persist($battle);
        $entMngr -> flush();

        return $this->render('battle/newWild.html.twig', [
            'battle' => $battle,
            'pkmn1' => $pkmn, 'pkdx1' => $pkmn -> getPokedex(),
            'pkmn2' => $wild, 'pkdx2' => $wildPkdx
        ]);
    }

    #[Route('/newSimple', name: 'app_battle_new_simple', methods: ['GET'])]
    public function newSimple(Request $request, EntityManagerInterface $entityManager){

    }

    #[Route('/newTeam', name: 'app_battle_new_team', methods: ['GET'])]
    public function newTeam(Request $request, EntityManagerInterface $entityManager){

    }

    #[Route('/hunt', name: 'app_battle_hunt', methods: ['GET'])]
    public function hunt(PokedexRepository $pokeRepo, Request $request, EntityManagerInterface $entityManager): Response {
        $pkdx = $pokeRepo -> generateWildPokemon();

        return $this -> render('battle/hunt.html.twig', [
            'pokedex' => $pkdx,
        ]);
    }
    
    #[Route('/hunt_end/{id}', name: 'app_battle_hunt_end', methods: ['GET', 'POST'])]
    public function huntEnd(int $id, PokedexRepository $pokeRepo, Request $request, EntityManagerInterface $entityManager): Response {
        $pkdx = $pokeRepo -> getPokedexById($id);

        if($request -> get('flee') !== null){
            $this -> addFlash('warning', 'Has huido como un cobarde del ' . $pkdx -> getName() . '.');
            return $this->redirectToRoute('app_main');
        }
        elseif($request -> get('hunt') !== null){
            if(rand(0, 9) > 3){
                $pkmn = new Pokemon();
                $pkmn -> setPokedex($pkdx);
                $pkmn -> setuser($this -> getUser());
                $pkmn -> setLevel(1);
                $pkmn -> setPower(10);
                $pkmn -> setIsAlive(true);

                $entityManager -> persist($pkmn);
                $entityManager -> flush();

                $this -> addFlash('warning', '¡Bravo! Has esclavizado un nuevo ' .  $pkdx -> getName() . 'puedes revisarlo en tu coleción.');
            }
            else{
                $this -> addFlash('warning', '¡Vaya! ' .  $pkdx -> getName() . ' se ha resistido a tus encantos.');
            }
        }

        return $this -> redirectToRoute('app_main');
    }

    #[Route('/{id}', name: 'app_battle', methods: ['GET'])]
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

