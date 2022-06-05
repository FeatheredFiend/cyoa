<?php

namespace App\Controller;

use App\Entity\ParagraphAction;
use App\Form\ParagraphActionType;
use App\Service\ParagraphActionRun;
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

    public function __construct(ParagraphActionRepository $paragraphactionRepository, ManagerRegistry $doctrine, ValidatorInterface $validator, PaginatorInterface $paginator, ParagraphActionRun $paragraphActionRun)
    {
        $this->paragraphactionRepository = $paragraphactionRepository;
        $this->validator = $validator;
        $this->paginator = $paginator;
        $this->doctrine = $doctrine;
        $this->paragraphActionRun = $paragraphActionRun;
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

    #[Route('/paragraphaction/edit/{gamebook}/{id}', name: 'paragraphaction_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action'])]
    public function edit(int $id, Request $request, string $title, string $gamebook): Response
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

}
