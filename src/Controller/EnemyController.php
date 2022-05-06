<?php

namespace App\Controller;

use App\Entity\Enemy;
use App\Form\EnemyType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EnemyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EnemyController extends AbstractController
{
    #[Route('/enemy/view', name: 'enemy_view', defaults: ['title' => 'View Enemy'])]
    public function index(EnemyRepository $enemyRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $enemyRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('enemy/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/enemy/create', name: 'enemy_create', defaults: ['title' => 'Create Enemy'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $enemy = new Enemy();

        $form = $this->createForm(EnemyType::class, $enemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($enemy);
            $em->flush();

            return $this->redirectToRoute('enemy_view');
        }
        return $this->render('enemy/create.html.twig', ['form' => $form->createView(),'enemy' => $enemy,'title' => $title]);

    }

    #[Route('/enemy/edit/{id}', name: 'enemy_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Enemy'])]
    public function edit(int $id, EnemyRepository $enemyRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $enemy = $enemyRepository
            ->find($id);


        $form = $this->createForm(EnemyType::class, $enemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($enemy);
            $em->flush();

            return $this->redirectToRoute('enemy_view');
        }

        return $this->render('enemy/edit.html.twig', ['enemy' => $enemy,'form' => $form->createView(),'title' => $title]);
    }
}
