<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie', name: 'movie_')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'get')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d{1,3}'], methods: 'get')]
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
           'movie' => $movie
        ]);
    }

    #[Route('/new', name: 'new', methods: ['get', 'post'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            $this->addFlash('success', 'Film créé avec succès.');

            return $this->redirectToRoute('movie_show', [
                'id' => $movie->getId()
            ]);
        }

        return $this->render('movie/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d{1,3}'], methods: ['get', 'post'])]
    public function update(Movie $movie, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "Le film {$movie->getName()} modifier avec succès.");

            return $this->redirectToRoute('movie_show', [
                'id' => $movie->getId()
            ]);
        }

        return $this->render('movie/update.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]);
    }

    #[Route('delete/{id}/{token}', name: 'delete', methods: 'get')]
    public function delete(Movie $movie, string $token, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $token)) {
            $manager->remove($movie);
            $manager->flush();

            $this->addFlash('success', 'Film supprimé avec succès.');
        }

        return $this->redirectToRoute('movie_index');
    }
}
