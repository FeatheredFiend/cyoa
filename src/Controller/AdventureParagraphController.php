<?php

namespace App\Controller;

use App\Entity\AdventureParagraph;
use App\Form\AdventureParagraphType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\AdventureParagraphRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdventureParagraphController extends AbstractController
{

    private $adventureparagraphRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(AdventureParagraphRepository $adventureparagraphRepository, PaginatorInterface $paginator, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        $this->adventureparagraphRepository = $adventureparagraphRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/adventureparagraph/view', name: 'adventureparagraph_view', defaults: ['title' => 'View Adventure Paragraph'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->adventureparagraphRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('adventure_paragraph/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/adventureparagraph/create', name: 'adventureparagraph_create', defaults: ['title' => 'Create Adventure Paragraph'])]
    public function create(Request $request, string $title): Response
    {
        $adventureparagraph = new AdventureParagraph();

        $form = $this->createForm(AdventureParagraphType::class, $adventureparagraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($adventureparagraph);
            $em->flush();

            return $this->redirectToRoute('adventureparagraph_view');
        }
        return $this->render('adventure_paragraph/create.html.twig', ['form' => $form->createView(),'adventureparagraph' => $adventureparagraph,'title' => $title]);

    }

    #[Route('/adventureparagraph/edit/{id}', name: 'adventureparagraph_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Adventure Paragraph'])]
    public function edit(int $id, Request $request, string $title): Response
    {
        $adventureparagraph = $this->adventureparagraphRepository
            ->find($id);

        $form = $this->createForm(AdventureParagraphType::class, $adventureparagraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($adventureparagraph);
            $em->flush();

            return $this->redirectToRoute('adventureparagraph_view');
        }

        return $this->render('adventure_paragraph/edit.html.twig', ['adventureparagraph' => $adventureparagraph,'form' => $form->createView(),'title' => $title]);
    }
}
