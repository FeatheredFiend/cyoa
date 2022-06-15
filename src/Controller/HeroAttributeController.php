<?php

namespace App\Controller;

use App\Entity\HeroAttribute;
use App\Form\HeroAttributeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\HeroAttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class HeroAttributeController extends AbstractController
{
    private $heroattributeRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   


    public function __construct(HeroAttributeRepository $heroattributeRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->heroattributeRepository = $heroattributeRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/heroattribute/view', name: 'heroattribute_view', defaults: ['title' => 'View Hero Attribute'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->heroattributeRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('hero_attribute/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/heroattribute/create', name: 'heroattribute_create', defaults: ['title' => 'Create Hero Attribute'])]
    public function create(Request $request, string $title): Response
    {
        $heroattribute = new HeroAttribute();

        $form = $this->createForm(HeroAttributeType::class, $heroattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($heroattribute);
            $em->flush();

            return $this->redirectToRoute('heroattribute_view');
        }
        return $this->render('hero_attribute/create.html.twig', ['form' => $form->createView(),'heroattribute' => $heroattribute,'title' => $title]);

    }

    #[Route('/heroattribute/edit/{id}', name: 'heroattribute_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Hero Attribute'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $heroattribute = $this->heroattributeRepository
            ->find($id);

        $form = $this->createForm(HeroAttributeType::class, $heroattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($heroattribute);
            $em->flush();

            return $this->redirectToRoute('heroattribute_view');
        }

        return $this->render('hero_attribute/edit.html.twig', ['heroattribute' => $heroattribute,'form' => $form->createView(),'title' => $title]);
    }
}
