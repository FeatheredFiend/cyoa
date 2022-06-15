<?php

namespace App\Controller;

use App\Entity\ParagraphActionTarget;
use App\Form\ParagraphActionTargetType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionTargetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionTargetController extends AbstractController
{

    private $paragraphactiontargetRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(ParagraphActionTargetRepository $paragraphactiontargetRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactiontargetRepository = $paragraphactiontargetRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/paragraphactiontarget/view', name: 'paragraphactiontarget_view', defaults: ['title' => 'View Paragraph Action Target'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactiontargetRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_target/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/paragraphactiontarget/create', name: 'paragraphactiontarget_create', defaults: ['title' => 'Create Paragraph Action Target'])]
    public function create(Request $request, string $title): Response
    {
        $paragraphactiontarget = new ParagraphActionTarget();

        $form = $this->createForm(ParagraphActionTargetType::class, $paragraphactiontarget);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactiontarget);
            $em->flush();

            return $this->redirectToRoute('paragraphactiontarget_view');
        }
        return $this->render('paragraph_action_target/create.html.twig', ['form' => $form->createView(),'paragraphactiontarget' => $paragraphactiontarget,'title' => $title]);

    }

    #[Route('/paragraphactiontarget/edit/{id}', name: 'paragraphactiontarget_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Target'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $paragraphactiontarget = $this->paragraphactiontargetRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionTargetType::class, $paragraphactiontarget);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactiontarget);
            $em->flush();

            return $this->redirectToRoute('paragraphactiontarget_view');
        }

        return $this->render('paragraph_action_target/edit.html.twig', ['paragraphactiontarget' => $paragraphactiontarget,'form' => $form->createView(),'title' => $title]);
    }
}
