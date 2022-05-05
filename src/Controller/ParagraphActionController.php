<?php

namespace App\Controller;

use App\Entity\ParagraphAction;
use App\Form\ParagraphActionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionController extends AbstractController
{
    #[Route('/paragraphaction/view', name: 'paragraphaction_view', defaults: ['title' => 'View Paragraph Action'])]
    public function index(ParagraphActionRepository $paragraphactionRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphactionRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('paragraph_action/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphaction/create', name: 'paragraphaction_create', defaults: ['title' => 'Create Paragraph Action'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphaction = new ParagraphAction();

        $form = $this->createForm(ParagraphActionType::class, $paragraphaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphaction);
            $em->flush();

            return $this->redirectToRoute('paragraphaction_view');
        }
        return $this->render('paragraph_action/create.html.twig', ['form' => $form->createView(),'paragraphaction' => $paragraphaction,'title' => $title]);

    }

    #[Route('/paragraphaction/edit/{id}', name: 'paragraphaction_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action'])]
    public function edit(int $id, ParagraphActionRepository $paragraphactionRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphaction = $paragraphactionRepository
            ->find($id);

        $paragraphaction->getName();


        $form = $this->createForm(ParagraphActionType::class, $paragraphaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphaction);
            $em->flush();

            return $this->redirectToRoute('paragraphaction_view');
        }

        return $this->render('paragraph_action/edit.html.twig', ['paragraphaction' => $paragraphaction,'form' => $form->createView(),'title' => $title]);
    }
}
