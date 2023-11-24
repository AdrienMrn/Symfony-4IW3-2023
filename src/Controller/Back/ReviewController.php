<?php

namespace App\Controller\Back;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/review')]
class ReviewController extends AbstractController
{
    #[Route('/', name: 'app_review_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('back/review/index.html.twig', [
            'reviews' => $reviewRepository->findBy(['owner' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_review_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new Review();
        $review->setOwner($this->getUser());
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('back_app_review_edit', ['id' => $review->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/review/new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_review_show', methods: ['GET'])]
    #[IsGranted('ROLE_MODERATOR')]
    public function show(Review $review): Response
    {
        return $this->render('back/review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_MODERATOR') or review.getOwner() === user")]
    public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_app_review_edit', ['id' => $review->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_review_delete', methods: ['POST'])]
    // #[Security("is_granted('ROLE_MODERATOR') or review.getOwner() === user")]
    #[IsGranted("DELETE", 'review')]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
