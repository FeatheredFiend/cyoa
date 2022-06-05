<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParagraphActionEquipmentRequiredController extends AbstractController
{
    #[Route('/paragraph/action/equipment/required', name: 'app_paragraph_action_equipment_required')]
    public function index(): Response
    {
        return $this->render('paragraph_action_equipment_required/index.html.twig', [
            'controller_name' => 'ParagraphActionEquipmentRequiredController',
        ]);
    }
}
