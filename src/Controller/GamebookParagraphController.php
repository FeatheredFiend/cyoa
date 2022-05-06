<?php

namespace App\Controller;

use App\Entity\GamebookParagraph;
use App\Form\GamebookParagraphType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\GamebookParagraphRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class GamebookParagraphController extends AbstractController
{
    #[Route('/gamebookparagraph/view', name: 'gamebookparagraph_view', defaults: ['title' => 'View GamebookParagraph'])]
    public function index(GamebookParagraphRepository $gamebookparagraphRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $gamebookparagraphRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('gamebook_paragraph/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/gamebookparagraph/create', name: 'gamebookparagraph_create', defaults: ['title' => 'Create GamebookParagraph'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $gamebookparagraph = new GamebookParagraph();

        $form = $this->createForm(GamebookParagraphType::class, $gamebookparagraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($gamebookparagraph);
            $em->flush();

            return $this->redirectToRoute('gamebookparagraph_view');
        }
        return $this->render('gamebook_paragraph/create.html.twig', ['form' => $form->createView(),'gamebookparagraph' => $gamebookparagraph,'title' => $title]);

    }

    #[Route('/gamebookparagraph/edit/{id}', name: 'gamebookparagraph_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit GamebookParagraph'])]
    public function edit(int $id, GamebookParagraphRepository $gamebookparagraphRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $gamebookparagraph = $gamebookparagraphRepository
            ->find($id);

        $form = $this->createForm(GamebookParagraphType::class, $gamebookparagraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($gamebookparagraph);
            $em->flush();

            return $this->redirectToRoute('gamebookparagraph_view');
        }

        return $this->render('gamebook_paragraph/edit.html.twig', ['gamebookparagraph' => $gamebookparagraph,'form' => $form->createView(),'title' => $title]);
    }
}
