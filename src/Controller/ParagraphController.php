<?php

namespace App\Controller;

use App\Entity\Paragraph;
use App\Form\ParagraphType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphController extends AbstractController
{
    #[Route('/paragraph/view', name: 'paragraph_view', defaults: ['title' => 'View Paragraph'])]
    public function index(ParagraphRepository $paragraphRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('paragraph/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraph/create', name: 'paragraph_create', defaults: ['title' => 'Create Paragraph'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $paragraph = new Paragraph();

        $form = $this->createForm(ParagraphType::class, $paragraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraph);
            $em->flush();

            return $this->redirectToRoute('paragraph_view');
        }
        return $this->render('paragraph/create.html.twig', ['form' => $form->createView(),'paragraph' => $paragraph,'title' => $title]);

    }

    #[Route('/paragraph/edit/{id}', name: 'paragraph_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph'])]
    public function edit(int $id, ParagraphRepository $paragraphRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $paragraph = $paragraphRepository
            ->find($id);

        $paragraph->getName();


        $form = $this->createForm(ParagraphType::class, $paragraph);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraph);
            $em->flush();

            return $this->redirectToRoute('paragraph_view');
        }

        return $this->render('paragraph/edit.html.twig', ['paragraph' => $paragraph,'form' => $form->createView(),'title' => $title]);
    }
}
