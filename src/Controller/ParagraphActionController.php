<?php

namespace App\Controller;

use App\Entity\ParagraphAction;
use App\Form\ParagraphActionType;
use App\Service\ParagraphActionRun;
use App\Service\CreateBattle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ParagraphActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionController extends AbstractController
{

    private $paragraphactionRepository;      
    private $doctrine;
    private $validator;
    private $paginator;
    private $paragraphActionRun;
    private $createBattle;

    public function __construct(ParagraphActionRepository $paragraphactionRepository, ManagerRegistry $doctrine, ValidatorInterface $validator, PaginatorInterface $paginator, ParagraphActionRun $paragraphActionRun, CreateBattle $createBattle)
    {
        $this->paragraphactionRepository = $paragraphactionRepository;
        $this->validator = $validator;
        $this->paginator = $paginator;
        $this->doctrine = $doctrine;
        $this->paragraphActionRun = $paragraphActionRun;
        $this->createBattle = $createBattle;
    }

    #[Route('/paragraphaction/view/{gamebook}/{paragraph}', name: 'paragraphaction_view', defaults: ['title' => 'View Paragraph Action'])]
    public function index(Request $request, string $title, int $paragraph, string $gamebook): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactionRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/paragraphaction/create/{gamebook}/{paragraph}', name: 'paragraphaction_create', defaults: ['title' => 'Create Paragraph Action'])]
    public function create(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $paragraphaction = new ParagraphAction();

        $form = $this->createForm(ParagraphActionType::class, $paragraphaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphaction);
            $em->flush();

            return $this->redirectToRoute('paragraphaction_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('paragraph_action/create.html.twig', [
            'form' => $form->createView(),
            'paragraphaction' => $paragraphaction,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/paragraphaction/edit/{gamebook}/{paragraph}/{id}', name: 'paragraphaction_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action'])]
    public function edit(int $id, Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphaction = $this->paragraphactionRepository
            ->find($id);


        $form = $this->createForm(ParagraphActionType::class, $paragraphaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphaction);
            $em->flush();

            return $this->redirectToRoute('paragraphaction_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('paragraph_action/edit.html.twig', [
            'form' => $form->createView(),
            'paragraphaction' => $paragraphaction,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/run-paragraph-action', name: 'run_paragraph_action', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listParagraphAction(Request $request )
    {
        // Get Entity manager and repository
        $em = $this->doctrine->getManager();
        
        $category = $request->query->get("category");
        $operator = $request->query->get("operator");
        $attribute = $request->query->get("attribute");
        $actionvalue = $request->query->get("actionvalue");
        $target = $request->query->get("target");
        $diceroll = $request->query->get("diceroll"); 
        $hero = $request->query->get("hero");   
        $paragraph = $request->query->get("paragraph");   
        $adventure = $request->query->get("adventure"); 



        if ($diceroll == 1) {
            $value = $actionvalue + rand(1,6);
        } elseif ($diceroll == 2) {
            $value = $actionvalue + rand(2,12);
        } else {
            $value = $actionvalue;
        }

        if ($category === "Attribute Change") {
            if ($target === "Player") {
                if ($operator === "Add") {
                    if ($attribute ===  "Skill") {
                        $RAW_QUERY = "UPDATE hero SET skill = skill + :skillValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('skillValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    } elseif ($attribute ===  "Stamina") {
                        $RAW_QUERY = "UPDATE hero SET stamina = stamina + :staminaValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('staminaValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    } elseif ($attribute ===  "Luck") {
                        $RAW_QUERY = "UPDATE hero SET luck = luck + :luckValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('luckValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    }
                } else if ($operator === "Remove") {
                    if ($attribute ===  "Skill") {
                        $RAW_QUERY = "UPDATE hero SET skill = skill - :skillValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('skillValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    } elseif ($attribute ===  "Stamina") {
                        $RAW_QUERY = "UPDATE hero SET stamina = stamina - :staminaValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('staminaValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    } elseif ($attribute ===  "Luck") {
                        $RAW_QUERY = "UPDATE hero SET luck = luck - :luckValue WHERE id = :player";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('luckValue', $value);
                        $statement->bindParam('player', $hero);
                        $statement->execute();
                    }


                }
            } elseif ($target === "Enemy") {
                $enemy = $this->findEnemy($paragraph);
                $adventureParagraph = $this->createBattle->findAdventureParagraph($adventure);

                $lastBattle = $this->createBattle->findLastBattle($adventureParagraph,$enemy);

                if (!$lastBattle) {
                    $this->createBattle->createBattleFromAction($adventure, $paragraph, $enemy, 0);
                }
                if ($operator === "Add") {
                    if ($attribute ===  "Skill") {
                        $RAW_QUERY = "UPDATE battle SET enemyskill = enemyskill + :skillValue ORDER BY id DESC LIMIT 1";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('skillValue', $value);
                        $statement->execute();
                    } elseif ($attribute ===  "Stamina") {
                        $RAW_QUERY = "UPDATE battle SET enemystamina = enemystamina + :staminaValue ORDER BY id DESC LIMIT 1";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('staminaValue', $value);
                        $statement->execute();
                    } elseif ($attribute ===  "Luck") {

                    }
                } else if ($operator === "Remove") {
                    if ($attribute ===  "Skill") {
                        $RAW_QUERY = "UPDATE battle SET enemyskill = enemyskill - :skillValue ORDER BY id DESC LIMIT 1";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('skillValue', $value);
                        $statement->execute();
                    } elseif ($attribute ===  "Stamina") {
                        $RAW_QUERY = "UPDATE battle SET enemystamina = enemystamina - :staminaValue ORDER BY id DESC LIMIT 1";        
                        $statement = $em->getConnection()->prepare($RAW_QUERY);
                        $statement->bindParam('staminaValue', $value);
                        $statement->execute();
                    } elseif ($attribute ===  "Luck") {

                    }


                }
            }
        } elseif ($category === "Battle") {

        } elseif ($category === "Item Check") {

        } elseif ($category === "Shop") {

        }
        $results = [
            'category' => $category,
            'operator' => $operator,
            'attribute' => $attribute,
            'actionvalue' => $value,
            'target' => $target,
            'diceroll' => $diceroll
        ];
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($results);

    }

    public function findEnemy($paragraph)
    {
        $em = $this->doctrine->getManager();
        $enemiesRepository = $em->getRepository("App\Entity\ParagraphActionEnemy");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $enemy = $enemiesRepository->createQueryBuilder("pae")
            ->select('IDENTITY(pae.enemy)')
            ->leftJoin('pae.paragraphaction', 'pa')
            ->leftJoin('pa.paragraph', 'p')
            ->andWhere('p.id = :paragraph')
            ->setParameter('paragraph', $paragraph)
            ->getQuery()
            ->getSingleScalarResult();

        return $enemy;
    }


}
