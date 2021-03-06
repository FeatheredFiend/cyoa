<?php

namespace App\Controller;

use App\Entity\ParagraphDirection;
use App\Form\ParagraphDirectionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphDirectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphDirectionController extends AbstractController
{

    private $paragraphdirectionRepository;
    private $paginator;
    private $doctrine;
    private $validator;

    public function __construct(ParagraphDirectionRepository $paragraphdirectionRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphdirectionRepository = $paragraphdirectionRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/paragraphdirection/view/{gamebook}/{paragraph}', name: 'paragraphdirection_view', defaults: ['title' => 'View Paragraph Direction'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphdirectionRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_direction/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/paragraphdirection/create/{gamebook}/{paragraph}', name: 'paragraphdirection_create', defaults: ['title' => 'Create Paragraph Direction'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphdirection = new ParagraphDirection();

        $form = $this->createForm(ParagraphDirectionType::class, $paragraphdirection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphdirection);
            $em->flush();

            return $this->redirectToRoute('paragraphdirection_view', ['gamebook' => $gamebook, 'paragraph' => $paragraphdirection->getParagraph()]);
        }
        return $this->render('paragraph_direction/create.html.twig', ['form' => $form->createView(),'paragraphdirection' => $paragraphdirection,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph]);

    }

    #[Route('/paragraphdirection/edit/{gamebook}/{id}', name: 'paragraphdirection_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Direction'])]
    public function edit(int $id, Request $request,string $title, string $gamebook): Response
    {
        $paragraphdirection = $this->paragraphdirectionRepository
            ->find($id);

        $form = $this->createForm(ParagraphDirectionType::class, $paragraphdirection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphdirection);
            $em->flush();

            return $this->redirectToRoute('paragraphdirection_view', ['gamebook' => $gamebook, 'paragraph' => $paragraphdirection->getParagraph()]);
        }

        return $this->render('paragraph_direction/edit.html.twig', ['paragraphdirection' => $paragraphdirection,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraphdirection->getParagraph()]);
    }
}
