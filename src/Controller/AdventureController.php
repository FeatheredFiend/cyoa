<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Form\AdventureType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\AdventureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdventureController extends AbstractController
{
    #[Route('/adventure/view', name: 'adventure_view', defaults: ['title' => 'View Adventure'])]
    public function index(AdventureRepository $adventureRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $adventureRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('adventure/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/adventure/create', name: 'adventure_create', defaults: ['title' => 'Create Adventure'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $adventure = new Adventure();

        $form = $this->createForm(AdventureType::class, $adventure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($adventure);
            $em->flush();

            return $this->redirectToRoute('adventure_view');
        }
        return $this->render('adventure/create.html.twig', ['form' => $form->createView(),'adventure' => $adventure,'title' => $title]);

    }

    #[Route('/adventure/edit/{id}', name: 'adventure_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Adventure'])]
    public function edit(int $id, AdventureRepository $adventureRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $adventure = $adventureRepository
            ->find($id);


        $form = $this->createForm(AdventureType::class, $adventure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($adventure);
            $em->flush();

            return $this->redirectToRoute('adventure_view');
        }

        return $this->render('adventure/edit.html.twig', ['adventure' => $adventure,'form' => $form->createView(),'title' => $title]);
    }
}
