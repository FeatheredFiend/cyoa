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

    private $paragraphactioncategoryRepository;
    private $paginator;
    private $doctrine;
    private $validator;

    public function __construct(ParagraphActionCategoryRepository $paragraphactioncategoryRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactioncategoryRepository = $paragraphactioncategoryRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/paragraphactioncategory/view', name: 'paragraphactioncategory_view', defaults: ['title' => 'View Paragraph Action Category'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactioncategoryRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_category/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphactioncategory/create', name: 'paragraphactioncategory_create', defaults: ['title' => 'Create Paragraph Action Category'])]
    public function create(Request $request, string $title): Response
    {
        $paragraphactioncategory = new ParagraphActionCategory();

        $form = $this->createForm(ParagraphActionCategoryType::class, $paragraphactioncategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactioncategory);
            $em->flush();

            return $this->redirectToRoute('paragraphactioncategory_view');
        }
        return $this->render('paragraph_action_category/create.html.twig', ['form' => $form->createView(),'paragraphactioncategory' => $paragraphactioncategory,'title' => $title]);

    }

    #[Route('/paragraphactioncategory/edit/{id}', name: 'paragraphactioncategory_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Category'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $paragraphactioncategory = $this->paragraphactioncategoryRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionCategoryType::class, $paragraphactioncategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactioncategory);
            $em->flush();

            return $this->redirectToRoute('paragraphactioncategory_view');
        }

        return $this->render('paragraph_action_category/edit.html.twig', ['paragraphactioncategory' => $paragraphactioncategory,'form' => $form->createView(),'title' => $title]);
    }
}
