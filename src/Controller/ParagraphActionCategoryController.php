<?php

namespace App\Controller;

use App\Entity\ParagraphActionCategory;
use App\Form\ParagraphActionCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionCategoryController extends AbstractController
{
    #[Route('/paragraphactioncategory/view', name: 'paragraphactioncategory_view', defaults: ['title' => 'View Paragraph Action Category'])]
    public function index(ParagraphActionCategoryRepository $paragraphactioncategoryRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphactioncategoryRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('paragraph_action_category/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphactioncategory/create', name: 'paragraphactioncategory_create', defaults: ['title' => 'Create Paragraph Action Category'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphactioncategory = new ParagraphActionCategory();

        $form = $this->createForm(ParagraphActionCategoryType::class, $paragraphactioncategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphactioncategory);
            $em->flush();

            return $this->redirectToRoute('paragraphactioncategory_view');
        }
        return $this->render('paragraph_action_category/create.html.twig', ['form' => $form->createView(),'paragraphactioncategory' => $paragraphactioncategory,'title' => $title]);

    }

    #[Route('/paragraphactioncategory/edit/{id}', name: 'paragraphactioncategory_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Category'])]
    public function edit(int $id, ParagraphActionCategoryRepository $paragraphactioncategoryRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphactioncategory = $paragraphactioncategoryRepository
            ->find($id);

        $paragraphactioncategory->getName();


        $form = $this->createForm(ParagraphActionCategoryType::class, $paragraphactioncategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphactioncategory);
            $em->flush();

            return $this->redirectToRoute('paragraphactioncategory_view');
        }

        return $this->render('paragraph_action_category/edit.html.twig', ['paragraphactioncategory' => $paragraphactioncategory,'form' => $form->createView(),'title' => $title]);
    }
}
