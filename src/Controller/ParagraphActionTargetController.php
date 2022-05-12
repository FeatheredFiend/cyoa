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
    #[Route('/paragraphactiontarget/view', name: 'paragraphactiontarget_view', defaults: ['title' => 'View Paragraph Action Target'])]
    public function index(ParagraphActionTargetRepository $paragraphactiontargetRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphactiontargetRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
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
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphactiontarget = new ParagraphActionTarget();

        $form = $this->createForm(ParagraphActionTargetType::class, $paragraphactiontarget);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphactiontarget);
            $em->flush();

            return $this->redirectToRoute('paragraphactiontarget_view');
        }
        return $this->render('paragraph_action_target/create.html.twig', ['form' => $form->createView(),'paragraphactiontarget' => $paragraphactiontarget,'title' => $title]);

    }

    #[Route('/paragraphactiontarget/edit/{id}', name: 'paragraphactiontarget_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Target'])]
    public function edit(int $id, ParagraphActionTargetRepository $paragraphactiontargetRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $paragraphactiontarget = $paragraphactiontargetRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionTargetType::class, $paragraphactiontarget);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphactiontarget);
            $em->flush();

            return $this->redirectToRoute('paragraphactiontarget_view');
        }

        return $this->render('paragraph_action_target/edit.html.twig', ['paragraphactiontarget' => $paragraphactiontarget,'form' => $form->createView(),'title' => $title]);
    }
}
