<?php

namespace App\Controller\Front;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(Mailer $mailer): Response
    {
        $mailer->sendTemplate(1, [['email' => 'test@test.fr', 'name' => 'test']], [
            'name' => 'test',
            'email' => 'test@test.fr)',
            'password' => 'test'
        ]);

        return $this->render('front/default/index.html.twig');
    }
}
