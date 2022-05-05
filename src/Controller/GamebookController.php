<?php

namespace App\Controller;

use App\Entity\Gamebook;
use App\Form\GamebookType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\GamebookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class GamebookController extends AbstractController
{
    #[Route('/gamebook/view', name: 'gamebook_view', defaults: ['title' => 'View Gamebook'])]
    public function index(GamebookRepository $gamebookRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $gamebookRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('gamebook/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/gamebook/create', name: 'gamebook_create', defaults: ['title' => 'Create Gamebook'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $gamebook = new Gamebook();

        $form = $this->createForm(GamebookType::class, $gamebook);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($gamebook);
            $em->flush();

            return $this->redirectToRoute('gamebook_view');
        }
        return $this->render('gamebook/create.html.twig', ['form' => $form->createView(),'gamebook' => $gamebook,'title' => $title]);

    }

    #[Route('/gamebook/edit/{id}', name: 'gamebook_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Gamebook'])]
    public function edit(int $id, GamebookRepository $gamebookRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $gamebook = $gamebookRepository
            ->find($id);

        $gamebook->getName();


        $form = $this->createForm(GamebookType::class, $gamebook);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($gamebook);
            $em->flush();

            return $this->redirectToRoute('gamebook_view');
        }

        return $this->render('gamebook/edit.html.twig', ['gamebook' => $gamebook,'form' => $form->createView(),'title' => $title]);
    }
}
