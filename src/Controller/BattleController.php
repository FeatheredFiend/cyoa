<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Form\BattleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\BattleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class BattleController extends AbstractController
{
    #[Route('/battle/view', name: 'battle_view', defaults: ['title' => 'View Battle'])]
    public function index(BattleRepository $battleRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $battleRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('battle/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/battle/create', name: 'battle_create', defaults: ['title' => 'Create Battle'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $battle = new Battle();

        $form = $this->createForm(BattleType::class, $battle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($battle);
            $em->flush();

            return $this->redirectToRoute('battle_view');
        }
        return $this->render('battle/create.html.twig', ['form' => $form->createView(),'battle' => $battle,'title' => $title]);

    }

    #[Route('/battle/edit/{id}', name: 'battle_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Battle'])]
    public function edit(int $id, BattleRepository $battleRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $battle = $battleRepository
            ->find($id);

        $battle->getName();


        $form = $this->createForm(BattleType::class, $battle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($battle);
            $em->flush();

            return $this->redirectToRoute('battle_view');
        }

        return $this->render('battle/edit.html.twig', ['battle' => $battle,'form' => $form->createView(),'title' => $title]);
    }
}
