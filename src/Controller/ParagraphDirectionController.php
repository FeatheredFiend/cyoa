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
    #[Route('/paragraphdirection/view', name: 'paragraphdirection_view', defaults: ['title' => 'View Paragraph Direction'])]
    public function index(ParagraphDirectionRepository $paragraphdirectionRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphdirectionRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('paragraph_direction/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphdirection/create', name: 'paragraphdirection_create', defaults: ['title' => 'Create Paragraph Direction'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphdirection = new ParagraphDirection();

        $form = $this->createForm(ParagraphDirectionType::class, $paragraphdirection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphdirection);
            $em->flush();

            return $this->redirectToRoute('paragraphdirection_view');
        }
        return $this->render('paragraph_direction/create.html.twig', ['form' => $form->createView(),'paragraphdirection' => $paragraphdirection,'title' => $title]);

    }

    #[Route('/paragraphdirection/edit/{id}', name: 'paragraphdirection_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Direction'])]
    public function edit(int $id, ParagraphDirectionRepository $paragraphdirectionRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphdirection = $paragraphdirectionRepository
            ->find($id);

        $form = $this->createForm(ParagraphDirectionType::class, $paragraphdirection);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphdirection);
            $em->flush();

            return $this->redirectToRoute('paragraphdirection_view');
        }

        return $this->render('paragraph_direction/edit.html.twig', ['paragraphdirection' => $paragraphdirection,'form' => $form->createView(),'title' => $title]);
    }
}
