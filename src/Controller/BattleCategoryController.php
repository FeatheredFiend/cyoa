<?php

namespace App\Controller;

use App\Entity\BattleCategory;
use App\Form\BattleCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\BattleCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class BattleCategoryController extends AbstractController
{

    private $battlecategoryRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(BattleCategoryRepository $battlecategoryRepository, PaginatorInterface $paginator, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        $this->battlecategoryRepository = $battlecategoryRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/battlecategory/view', name: 'battlecategory_view', defaults: ['title' => 'View Battle Category'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $battlecategoryRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('battle_category/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/battlecategory/create', name: 'battlecategory_create', defaults: ['title' => 'Create Battle Category'])]
    public function create(Request $request, string $title): Response
    {
        $battlecategory = new BattleCategory();

        $form = $this->createForm(BattleCategoryType::class, $battlecategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($battlecategory);
            $em->flush();

            return $this->redirectToRoute('battlecategory_view');
        }
        return $this->render('battle_category/create.html.twig', ['form' => $form->createView(),'battlecategory' => $battlecategory,'title' => $title]);

    }

    #[Route('/battlecategory/edit/{id}', name: 'battlecategory_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Battle Category'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $battlecategory = $battlecategoryRepository
            ->find($id);


        $form = $this->createForm(BattleCategoryType::class, $battlecategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($battlecategory);
            $em->flush();

            return $this->redirectToRoute('battlecategory_view');
        }

        return $this->render('battle_category/edit.html.twig', ['battlecategory' => $battlecategory,'form' => $form->createView(),'title' => $title]);
    }
}
