<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Form\AdventureType;
use App\Service\CreateHero;
use App\Service\StartAdventure;
use App\Service\NextParagraph;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\AdventureRepository;
use App\Repository\BattleRepository;
use App\Repository\HeroEquipmentRepository;
use App\Repository\HeroRepository;
use App\Repository\ParagraphRepository;
use App\Repository\ParagraphActionRepository;
use App\Repository\ParagraphDirectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdventureController extends AbstractController
{

    private $adventureRepository;
    private $heroequipmentRepository;
    private $heroRepository;
    private $paragraphRepository;
    private $paragraphactionRepository;
    private $paragraphdirectionRepository; 
    private $battleRepository;    
    private $paginator;
    private $doctrine;
    private $validator;
    private $createHero;
    private $startAdventure;
    private $nextParagraph;

    public function __construct(AdventureRepository $adventureRepository, HeroRepository $heroRepository, HeroEquipmentRepository $heroequipmentRepository, ParagraphRepository $paragraphRepository, ParagraphActionRepository $paragraphactionRepository, ParagraphDirectionRepository $paragraphdirectionRepository, BattleRepository $battleRepository, PaginatorInterface $paginator, ManagerRegistry $doctrine, ValidatorInterface $validator, CreateHero $createHero, StartAdventure $startAdventure, NextParagraph $nextParagraph)
    {
        $this->adventureRepository = $adventureRepository;
        $this->heroequipmentRepository = $heroequipmentRepository;
        $this->heroRepository = $heroRepository;
        $this->paragraphRepository = $paragraphRepository;
        $this->paragraphactionRepository = $paragraphactionRepository;
        $this->paragraphdirectionRepository = $paragraphdirectionRepository;
        $this->battleRepository = $battleRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->createHero = $createHero;
        $this->startAdventure = $startAdventure;
        $this->nextParagraph = $nextParagraph;
    }

    #[Route('/adventure/view', name: 'adventure_view', defaults: ['title' => 'View Adventure'])]
    public function index(string $title, Request $request): Response
    {
        $q = $request->query->get('q');
        $user = $this->getUser();
        $user = $user->getId();
        $queryBuilder = $this->adventureRepository->getWithSearchQueryBuilderView($q, $user);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('adventure/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/adventure/create', name: 'adventure_create', defaults: ['title' => 'Create Adventure'])]
    public function create(string $title, Request $request): Response
    {
        $adventure = new Adventure();

        $form = $this->createForm(AdventureType::class, $adventure);
        $form->remove('timeelapsed');
        $form->remove('hero');
        $form->remove('progressparagraph');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adventure->setTimeelapsed(0);
            $this->createHero->createHero($adventure->getName());
            $heroId = $this->createHero->getHero();
            $hero = $this->heroRepository->find($heroId);            
            $adventure->setHero($hero);
            $adventure->setName($hero->getName() . " " . date("d-m-Y"));
            $adventure->setProgressparagraph(1);
            // Save
            $em = $this->doctrine->getManager();
            $em->persist($adventure);
            $em->flush();
            $this->startAdventure->startAdventure($adventure->getId(), 1);

            return $this->redirectToRoute('adventure_view');
        }
        return $this->render('adventure/create.html.twig', ['form' => $form->createView(),'adventure' => $adventure,'title' => $title]);

    }

    #[Route('/adventure/edit/{id}', name: 'adventure_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Adventure'])]
    public function edit(int $id, string $title, Request $request): Response
    {
        $adventure = $this->adventureRepository
            ->find($id);


        $form = $this->createForm(AdventureType::class, $adventure);
        $form->remove('timeelapsed');
        $form->remove('hero');        
        $form->remove('progressparagraph');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($adventure);
            $em->flush();

            return $this->redirectToRoute('adventure_view');
        }

        return $this->render('adventure/edit.html.twig', ['adventure' => $adventure,'form' => $form->createView(),'title' => $title]);
    }

    #[Route('/adventure/play/{adventure}/{paragraph}', name: 'adventure_play', defaults: ['title' => 'Play Adventure'])]
    public function play(int $adventure, int $paragraph, string $title, Request $request): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->adventureRepository->getWithSearchQueryBuilderHeroStats($q, $adventure);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderEquipment = $this->heroequipmentRepository->getWithSearchQueryBuilderPlay($q, $adventure);

        $paginationEquipment = $this->paginator->paginate(
            $queryBuilderEquipment, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderParagraph = $this->paragraphRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationParagraph = $this->paginator->paginate(
            $queryBuilderParagraph, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderParagraphAction = $this->paragraphactionRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationParagraphAction = $this->paginator->paginate(
            $queryBuilderParagraphAction, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderBattle = $this->battleRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationBattle = $this->paginator->paginate(
            $queryBuilderBattle, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderParagraphDirection = $this->paragraphdirectionRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationParagraphDirection = $this->paginator->paginate(
            $queryBuilderParagraphDirection, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        


        return $this->render('adventure/play.html.twig', [
            'pagination' => $pagination,
            'paginationEquipment' => $paginationEquipment,
            'paginationParagraph' => $paginationParagraph,
            'paginationParagraphAction' => $paginationParagraphAction,
            'paginationParagraphDirection' => $paginationParagraphDirection,
            'paginationBattle' => $paginationBattle,
            'title' => $title,
            'adventure' => $adventure,
            'paragraph' => $paragraph,
        ]);
    }

    
    #[Route('/adventure/next/{adventure}/{paragraph}', name: 'adventure_next', defaults: ['title' => 'Next Adventure'])]
    public function nextParagraph(int $adventure, int $paragraph, string $title, Request $request): Response
    {
        $this->nextParagraph->nextParagraph($adventure, $paragraph);
        $this->startAdventure->adventureProgress($adventure, $paragraph);

        return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraph]);
    }
}
