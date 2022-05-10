<?php

namespace App\Controller;

use App\Entity\Adventure;
use App\Form\AdventureType;
use App\Service\CreateHero;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\AdventureRepository;
use App\Repository\HeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdventureController extends AbstractController
{

    private $adventureRepository;
    private $paginator;
    private $doctrine;
    private $validator;
    private $createHero;

    public function __construct(AdventureRepository $adventureRepository, HeroRepository $heroRepository, PaginatorInterface $paginator, ManagerRegistry $doctrine, ValidatorInterface $validator, CreateHero $createHero)
    {
        $this->adventureRepository = $adventureRepository;
        $this->heroRepository = $heroRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->createHero = $createHero;
    }

    #[Route('/adventure/view', name: 'adventure_view', defaults: ['title' => 'View Adventure'])]
    public function index(string $title, Request $request): Response
    {
        $q = $request->query->get('q');
        $user = $this->getUser();
        $user = $user->getId();
        $queryBuilder = $this->adventureRepository->getWithSearchQueryBuilderView($q, $user);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('adventure/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/adventure/create', name: 'adventure_create', defaults: ['title' => 'Create Adventure'])]
    public function create(string $title, Request $request): Response
    {
        $adventure = new Adventure();

        $form = $this->createForm(AdventureType::class, $adventure);
        $form->remove('timeelapsed');
        $form->remove('hero');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adventure->setTimeelapsed(0);
            $this->createHero->createHero($adventure->getName());
            $heroId = $this->createHero->getHero();
            $hero = $this->heroRepository->find($heroId);            
            $adventure->setHero($hero);
            $adventure->setName($hero->getName() . " " . date("d-m-Y"));
            // Save
            $em = $this->doctrine->getManager();
            $em->persist($adventure);
            $em->flush();

            return $this->redirectToRoute('adventure_view');
        }
        return $this->render('adventure/create.html.twig', ['form' => $form->createView(),'adventure' => $adventure,'title' => $title]);

    }

    #[Route('/adventure/edit/{id}', name: 'adventure_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Adventure'])]
    public function edit(int $id, string $title, Request $request): Response
    {
        $adventure = $this->adventureRepository
            ->find($id);


        $form = $this->createForm(AdventureType::class, $adventure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($adventure);
            $em->flush();

            return $this->redirectToRoute('adventure_view');
        }

        return $this->render('adventure/edit.html.twig', ['adventure' => $adventure,'form' => $form->createView(),'title' => $title]);
    }
}
