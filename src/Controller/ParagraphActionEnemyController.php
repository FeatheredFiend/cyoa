<?php

namespace App\Controller;

use App\Entity\ParagraphActionEnemy;
use App\Form\ParagraphActionEnemyType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionEnemyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionEnemyController extends AbstractController
{

    private $paragraphactionenemyRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(ParagraphActionEnemyRepository $paragraphactionenemyRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactionenemyRepository = $paragraphactionenemyRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/paragraphactionenemy/view/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionenemy_view', defaults: ['title' => 'View Paragraph Action Enemy'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactionenemyRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_enemy/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'paragraphaction' => $paragraphaction
        ]);
    }

    #[Route('/paragraphactionenemy/create/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionenemy_create', defaults: ['title' => 'Create Paragraph Action Enemy'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $paragraphactionenemy = new ParagraphActionEnemy();

        $form = $this->createForm(ParagraphActionEnemyType::class, $paragraphactionenemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionenemy);
            $em->flush();

            return $this->redirectToRoute('paragraphactionenemy_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }
        return $this->render('paragraph_action_enemy/create.html.twig', ['form' => $form->createView(),'paragraphactionenemy' => $paragraphactionenemy,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);

    }

    #[Route('/paragraphactionenemy/edit/{gamebook}/{paragraph}/{paragraphaction}/{id}', name: 'paragraphactionenemy_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Enemy'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraphaction, int $paragraph): Response
    {
        $paragraphactionenemy = $this->paragraphactionenemyRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionEnemyType::class, $paragraphactionenemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionenemy);
            $em->flush();

            return $this->redirectToRoute('paragraphactionenemy_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }

        return $this->render('paragraph_action_enemy/edit.html.twig', ['paragraphactionenemy' => $paragraphactionenemy,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
    }
}
