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

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie_index', methods: 'get')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movie/{id}', name: 'movie_show', requirements: ['id' => '\d{1,3}'], methods: 'get')]
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
           'movie' => $movie
        ]);
    }

    #[Route('/movie/new', name: 'movie_new', methods: ['get', 'post'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/movie/update/{id}', name: 'movie_update', requirements: ['id' => '\d{1,3}'], methods: ['get', 'post'])]
    public function update(Movie $movie, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            return $this->redirectToRoute('movie_update', [
                'id' => $movie->getId()
            ]);
        }

        return $this->render('movie/update.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]);
    }
}
