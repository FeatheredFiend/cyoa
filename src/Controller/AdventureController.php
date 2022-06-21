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
use App\Repository\EnemyRepository;
use App\Repository\HeroEquipmentRepository;
use App\Repository\HeroRepository;
use App\Repository\HeroSpellRepository;
use App\Repository\MerchantRepository;
use App\Repository\ParagraphRepository;
use App\Repository\ParagraphEquipmentRepository;
use App\Repository\ParagraphActionRepository;
use App\Repository\ParagraphDirectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdventureController extends AbstractController
{

    private $adventureRepository;
    private $heroequipmentRepository;
    private $heroRepository;
    private $herospellRepository;
    private $paragraphRepository;
    private $paragraphequipmentRepository;
    private $paragraphactionRepository;
    private $paragraphdirectionRepository;
    private $battleRepository;
    private $enemyRepository;
    private $merchantRepository;
    private $paginator;
    private $doctrine;
    private $validator;
    private $createHero;
    private $startAdventure;
    private $nextParagraph;

    public function __construct(AdventureRepository $adventureRepository, MerchantRepository $merchantRepository, HeroRepository $heroRepository, HeroSpellRepository $herospellRepository, HeroEquipmentRepository $heroequipmentRepository, ParagraphRepository $paragraphRepository, ParagraphEquipmentRepository $paragraphequipmentRepository, ParagraphActionRepository $paragraphactionRepository, ParagraphDirectionRepository $paragraphdirectionRepository, EnemyRepository $enemyRepository, BattleRepository $battleRepository, PaginatorInterface $paginator, ManagerRegistry $doctrine, ValidatorInterface $validator, CreateHero $createHero, StartAdventure $startAdventure, NextParagraph $nextParagraph)
    {
        $this->adventureRepository = $adventureRepository;
        $this->heroequipmentRepository = $heroequipmentRepository;
        $this->heroRepository = $heroRepository;
        $this->herospellRepository = $herospellRepository;
        $this->paragraphRepository = $paragraphRepository;
        $this->paragraphequipmentRepository = $paragraphequipmentRepository;
        $this->paragraphactionRepository = $paragraphactionRepository;
        $this->paragraphdirectionRepository = $paragraphdirectionRepository;
        $this->battleRepository = $battleRepository;
        $this->enemyRepository = $enemyRepository;
        $this->merchantRepository = $merchantRepository;
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
            $heroId = $this->createHero->getMaxHero();
            $hero = $this->heroRepository->find($heroId);
            $adventure->setHero($hero);
            $adventure->setName($hero->getName() . " " . date("d-m-Y"));
            $adventure->setProgressparagraph(1);
            // Save
            $em = $this->doctrine->getManager();
            $em->persist($adventure);
            $em->flush();
            $this->startAdventure->startAdventure($adventure->getId());
            $this->startAdventure->stockMerchant($adventure->getId());

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

        $queryBuilderHeroEquipment = $this->heroequipmentRepository->getWithSearchQueryBuilderPlay($q, $adventure);

        $paginationHeroEquipment = $this->paginator->paginate(
            $queryBuilderHeroEquipment, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderHeroSpell = $this->herospellRepository->getWithSearchQueryBuilderPlay($q, $adventure);

        $paginationHeroSpell = $this->paginator->paginate(
            $queryBuilderHeroSpell, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderParagraphEquipment = $this->paragraphequipmentRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationParagraphEquipment = $this->paginator->paginate(
            $queryBuilderParagraphEquipment, /* query NOT result */
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

        $queryBuilderMerchant = $this->merchantRepository->getWithSearchQueryBuilderPlay($q, $paragraph);

        $paginationMerchant = $this->paginator->paginate(
            $queryBuilderMerchant, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderEnemy = $this->enemyRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationEnemy = $this->paginator->paginate(
            $queryBuilderEnemy, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $queryBuilderBattle = $this->battleRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationBattle = $this->paginator->paginate(
            $queryBuilderBattle, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            1/*limit per page*/
        );

        $queryBuilderParagraphDirection = $this->paragraphdirectionRepository->getWithSearchQueryBuilderPlay($q, $adventure, $paragraph);

        $paginationParagraphDirection = $this->paginator->paginate(
            $queryBuilderParagraphDirection, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );



        return $this->render('adventure/play.html.twig', [
            'pagination' => $pagination,
            'paginationHeroEquipment' => $paginationHeroEquipment,
            'paginationHeroSpell' => $paginationHeroSpell,
            'paginationEquipment' => $paginationParagraphEquipment,
            'paginationParagraph' => $paginationParagraph,
            'paginationParagraphAction' => $paginationParagraphAction,
            'paginationParagraphDirection' => $paginationParagraphDirection,
            'paginationEnemy' => $paginationEnemy,
            'paginationBattle' => $paginationBattle,
            'paginationMerchant' => $paginationMerchant,
            'title' => $title,
            'adventure' => $adventure,
            'paragraph' => $paragraph,
        ]);
    }

    
    #[Route('/adventure/next/{adventure}/{paragraph}', name: 'adventure_next', defaults: ['title' => 'Next Adventure'])]
    public function nextParagraph(int $adventure, int $paragraph, string $title, Request $request): Response
    {
        $this->nextParagraph->nextParagraph($adventure, $paragraph);
        $gamebook = $this->nextParagraph->getAdventureGamebook($adventure);
        $paragraphId = $this->nextParagraph->getGamebookParagraph($gamebook, $paragraph);

        return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraphId]);
    }

}
