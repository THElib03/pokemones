<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Form\PokedexType;
use App\Repository\PokedexRepository;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    #[Route(name: 'app_pokedex_index', methods: ['GET'])]
    public function index(PokedexRepository $pokedexRepository): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'pokedexes' => $pokedexRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pokedex_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/images')] string $imageDirectory
    ): Response {
        $pokedex = new Pokedex();
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $slugger->slug($originalFilename) . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    throw new Error($e->getMessage());
                }
                $pokedex->setImage($newFilename);
            }
            $entityManager->persist($pokedex);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/new.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokedex_show', methods: ['GET'])]
    public function show(Pokedex $pokedex): Response
    {
        return $this->render('pokedex/show.html.twig', [
            'pokedex' => $pokedex,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokedex_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/edit.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokedex_delete', methods: ['POST'])]
    public function delete(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pokedex->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokedex);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
    }
}
