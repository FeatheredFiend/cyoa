<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParagraphDirectionEquipmentRequiredController extends AbstractController
{
    #[Route('/paragraph/direction/equipment/required', name: 'app_paragraph_direction_equipment_required')]
    public function index(): Response
    {
        return $this->render('paragraph_direction_equipment_required/index.html.twig', [
            'controller_name' => 'ParagraphDirectionEquipmentRequiredController',
        ]);
    }
}
