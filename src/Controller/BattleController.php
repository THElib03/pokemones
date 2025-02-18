<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Pokemon;
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
        $pkmn = $pkmnRepo -> getPokemonById($id);

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

    #[Route('/hunt', name: 'app_battle_hunt', methods: ['GET', 'POST'])]
    public function hunt(PokedexRepository $pkdxRepo, PokemonRepository $pkmnRepo, Request $request, EntityManagerInterface $entityManager): Response {
        $pkdx = $pkdxRepo -> generateWildPokemon();
        $id = -1;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $pkmn = $pkmnRepo -> getPokemonById($_POST['pkmn']);
            $pkdx = $pkmn -> getPokedex();
            $id = $pkmn -> getId();
        }

        return $this -> render('battle/hunt.html.twig', [
            'pokedex' => $pkdx,
            'id' => $id,
        ]);
    }

    #[Route('/huntEnd', name: 'app_battle_hunt_end', methods: ['POST'])]
    public function huntEnd(PokedexRepository $pkdxRepo, PokemonRepository $pkmnRepo, Request $request, EntityManagerInterface $entityManager): Response {
        $pkdx = $pkdxRepo -> getPokedexById($request -> get('id'));

        if($request -> get('flee') !== null){
            $this -> addFlash('warning', 'Has huido como un cobarde del ' . $pkdx -> getName() . '.');
            return $this->redirectToRoute('app_main');
        }
        elseif($request -> get('hunt') !== null){
            if(rand(0, 9) > 3){
                if($request -> get('pkmn') === null){
                    $pkmn = new Pokemon();
                    $pkmn -> setPokedex($pkdx);
                    $pkmn -> setuser($this -> getUser());
                    $pkmn -> setLevel(1);
                    $pkmn -> setPower(10);
                    $pkmn -> setIsAlive(true);

                    $this -> addFlash('warning', '¡Bravo! Has esclavizado un nuevo ' .  $pkdx -> getName() . 'puedes revisarlo en tu coleción.');
                }
                else{
                    $pkmn = $pkmnRepo -> getPokemonById($request -> get('pkmn'));
                    $pkmn -> setUser($this -> getUser());
                    $this -> addFlash('warning', '¡Bravo! ' . $pkdx -> getName() . ' es ahora tu nuevo esclavo, disfrútalo.');
                }
                
                $entityManager -> persist($pkmn);
                $entityManager -> flush();
                return $this -> redirectToRoute('app_pokemon_colection');
            }
            else{
                $this -> addFlash('warning', '¡Vaya! ' .  $pkdx -> getName() . ' se ha resistido a tus encantos.');
            }
        }

        return $this -> redirectToRoute('app_main');
    }

    #[Route('end_options/{id}', name: 'app_battle_end_options', methods: ['GET', 'POST'])]
    public function endOptions(int $id, PokemonRepository $pkmnRepo, BattleRepository $bttlRepo, Request $request, EntityManagerInterface $entMngr): Response {
        $battle = $bttlRepo -> getBattleById($id);
        if($battle -> getResult() === null || $battle -> getState() === 4){
            $this -> addFlash('warning', 'This battle has already ended and no more prizes can be collected.');
            $this -> redirectToRoute('app_pokemon_colection');
        }

        $dead = $pkmnRepo -> getDeadPokemones($this -> getUser());

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $pkmn = $pkmnRepo -> getPokemonById($_POST['pkmn']);

            if(isset($_POST['levelup'])){
                $pkmn -> setLevel($pkmn -> getLevel() + 1);
            }
            elseif(isset($_POST['revive'])){
                $pkmn -> setIsAlive(true);
            }

            $battle -> setState(4);
            $entMngr -> persist($battle);
            $entMngr -> persist($pkmn);
            $entMngr -> flush();

            $this -> redirectToRoute('app_pokemon_colection');
        }

        return $this -> render('battle/endOptions.html.twig', [
            'battle' => $battle,
            'deadPkmn' => $dead,
        ]);
    }

    #[Route('/confirm/{id}', name: 'app_battle_confirm', methods: ['POST'])]
    public function confirm(int $id, Request $request, EntityManagerInterface $entMngr){
        $battle = $entMngr -> find(Battle::class, $id);

        if($this -> getUser() === $battle->getUser1() || $this -> getUser() === $battle->getUser2()){
            $cnfrmtn = $battle -> getConfirm();

            if($this -> getUser() === $battle -> getUser1()){
                $cnfrmtn[0] = true;                
            }
            elseif($this -> getUser() === $battle -> getUser2()){
                $cnfrmtn[1] = true;
            }

            $battle -> setConfirm($cnfrmtn);

            if($cnfrmtn[0] && $cnfrmtn[1]){
                $battle -> setState(3);

                $entMngr -> persist($battle);
                $entMngr -> flush();
                return $this -> redirectToRoute('app_battle_end', ['id' => $battle -> getId()]);
            }
            elseif(sizeof($battle -> getPokemon2()) > 1){
                $battle -> setState(2);
            }
            elseif($battle -> getUser2() -> getId() != 0){
                $battle -> setState(1);
            }

            $this -> addFlash('warning', 'Waiting for ' . $battle->getUser2() -> getUsername() . ' to confirm the battle, you can check later in your battle history.');
        }
        else{
            $this -> addFlash('warning', 'Something went wrong: not allowed in this battle.');
        }

        $entMngr -> persist($battle);
        $entMngr -> flush();
        return $this->redirectToRoute('app_pokemon_colection');
    }

    #[Route('/end/{id}', name: 'app_battle_end', methods: ['GET'])]
    public function battleEnd(int $id, BattleRepository $bttlRepo, Request $request, EntityManagerInterface $entMngr): Response{
        $battle = $bttlRepo -> getBattleById($id);
        if($battle -> getState() < 3 || $battle -> getResult() !== null){
            $this -> addFlash('warning', 'This battle cannot be fought yet, please wait before all players confirm.');
            return $this->redirectToRoute('app_pokemon_colection');
        }
        elseif(sizeof($battle -> getPokemon1()) != sizeof($battle -> getPokemon2())){
            $this -> addFlash('warning', 'Something went wrong: pokemon count mismatch.');
            return $this->redirectToRoute('app_pokemon_colection');
        }

        $count = 0;
        for($i=0; $i < sizeof($battle -> getPokemon1()); $i++){ 
            $punch1 = $battle -> getPokemon1()[$i] -> getLevel() * $battle -> getPokemon1()[$i] -> getPower();
            $punch2 = $battle -> getPokemon2()[$i] -> getLevel() * $battle -> getPokemon2()[$i] -> getPower();

            if($punch1 > $punch2){
                $count++;
                $battle -> getPokemon2()[$i] -> setIsAlive(false);
            }
            elseif($punch1 < $punch2){
                $count--;
                $battle -> getPokemon1()[$i] -> setIsAlive(false);
            }
        }

        if($count > 0){
            $battle -> setResult($battle -> getUser1() -> getId());
        }
        elseif($count < 0){
            if($battle -> getUser2() === null){
                $battle -> setResult(-1);
            }    
            else{
                $battle -> setResult($battle -> getUser2() -> getId());
            }
        }
        else{
            $battle -> setResult(-1);
        }

        $entMngr -> persist($battle);
        $entMngr -> flush();

        return $this -> redirectToRoute('app_battle_end_options', ['id' => $battle -> getId()]);
    }

    #[Route('/{id}', name: 'app_battle', methods: ['GET'])]
    public function show(Battle $battle): Response
    {
        return $this->render('battle/show.html.twig', [
            'battle' => $battle,
        ]);
    }
}

