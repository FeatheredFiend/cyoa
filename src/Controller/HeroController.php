<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Form\HeroType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\HeroRepository;
use App\Repository\HeroEquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class HeroController extends AbstractController
{
    #[Route('/hero/view/{gamebook}/{hero}/{adventure}', name: 'hero_view', defaults: ['title' => 'View Hero'])]
    public function index(HeroRepository $heroRepository,HeroEquipmentRepository $heroequipmentRepository, Request $request, PaginatorInterface $paginator, string $title, string $hero, string $gamebook, string $adventure): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $heroRepository->getWithSearchQueryBuilderView($q, $hero);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $queryBuilderEq = $heroequipmentRepository->getWithSearchQueryBuilderView($q, $hero);

        $paginationEq = $paginator->paginate(
            $queryBuilderEq, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('hero/view.html.twig', [
            'pagination' => $pagination,
            'paginationEq' => $paginationEq,
            'title' => $title,
            'gamebook' => $gamebook,
            'adventure' => $adventure
        ]);
    }

    #[Route('/hero/create', name: 'hero_create', defaults: ['title' => 'Create Hero'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $hero = new Hero();

        $form = $this->createForm(HeroType::class, $hero);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($hero);
            $em->flush();

            return $this->redirectToRoute('hero_view');
        }
        return $this->render('hero/create.html.twig', ['form' => $form->createView(),'hero' => $hero,'title' => $title]);

    }

    #[Route('/hero/edit/{id}', name: 'hero_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Hero'])]
    public function edit(int $id, HeroRepository $heroRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $hero = $heroRepository
            ->find($id);

        $form = $this->createForm(HeroType::class, $hero);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($hero);
            $em->flush();

            return $this->redirectToRoute('hero_view');
        }

        return $this->render('hero/edit.html.twig', ['hero' => $hero,'form' => $form->createView(),'title' => $title]);
    }
}
