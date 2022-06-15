<?php

namespace App\Controller;

use App\Entity\ParagraphActionOperator;
use App\Form\ParagraphActionOperatorType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionOperatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionOperatorController extends AbstractController
{

    private $paragraphactionoperatorRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(ParagraphActionOperatorRepository $paragraphactionoperatorRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactionoperatorRepository = $paragraphactionoperatorRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/paragraphactionoperator/view', name: 'paragraphactionoperator_view', defaults: ['title' => 'View Paragraph Action Operator'])]
    public function index(ParagraphActionOperatorRepository $paragraphactionoperatorRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactionoperatorRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_operator/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphactionoperator/create', name: 'paragraphactionoperator_create', defaults: ['title' => 'Create Paragraph Action Operator'])]
    public function create(Request $request, string $title): Response
    {
        $paragraphactionoperator = new ParagraphActionOperator();

        $form = $this->createForm(ParagraphActionOperatorType::class, $paragraphactionoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionoperator);
            $em->flush();

            return $this->redirectToRoute('paragraphactionoperator_view');
        }
        return $this->render('paragraph_action_operator/create.html.twig', ['form' => $form->createView(),'paragraphactionoperator' => $paragraphactionoperator,'title' => $title]);

    }

    #[Route('/paragraphactionoperator/edit/{id}', name: 'paragraphactionoperator_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Operator'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $paragraphactionoperator = $this->paragraphactionoperatorRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionOperatorType::class, $paragraphactionoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionoperator);
            $em->flush();

            return $this->redirectToRoute('paragraphactionoperator_view');
        }

        return $this->render('paragraph_action_operator/edit.html.twig', ['paragraphactionoperator' => $paragraphactionoperator,'form' => $form->createView(),'title' => $title]);
    }
}
