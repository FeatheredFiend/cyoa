<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Service\CreateBattle;
use App\Service\NextBattle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\BattleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BattleController extends AbstractController
{

    private $battleRepository;      
    private $doctrine;
    private $validator;
    private $createBattle;
    private $nextBattle;

    public function __construct(BattleRepository $battleRepository, ManagerRegistry $doctrine, ValidatorInterface $validator, CreateBattle $createBattle, NextBattle $nextBattle)
    {
        $this->battleRepository = $battleRepository;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->createBattle = $createBattle;
        $this->nextBattle = $nextBattle;
    }

    #[Route('/battle/create/{adventure}/{paragraph}/{enemy}/{luck}', name: 'battle_create', defaults: ['title' => 'Create Battle'])]
    public function create(Request $request, string $title, int $adventure, int $paragraph, int $enemy, int $luck): Response
    {

        $this->createBattle->createBattle($adventure, $paragraph, $enemy, $luck);

        return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraph]);
    }

    #[Route('/battle/next/{adventure}/{paragraph}/{battle}/{luck}', name: 'battle_next', defaults: ['title' => 'Battle Next Round'])]
    public function next(Request $request, string $title, int $adventure, int $paragraph, int $battle, int $luck): Response
    {

        $this->nextBattle->nextBattle($adventure, $battle, $luck);

        return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraph]);
    }

}
