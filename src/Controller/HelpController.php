<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelpController extends AbstractController
{
    #[Route('/help', name: 'help_index', defaults: ['title' => 'Help'])]
    public function index(string $title): Response
    {
        return $this->render('help/index.html.twig', [
            'title' => $title
        ]);
    }
}
