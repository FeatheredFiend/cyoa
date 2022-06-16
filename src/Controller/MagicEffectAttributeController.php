<?php

namespace App\Controller;

use App\Entity\MagicEffectAttribute;
use App\Form\MagicEffectAttributeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MagicEffectAttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MagicEffectAttributeController extends AbstractController
{
    private $magiceffectattributeRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;


    public function __construct(MagicEffectAttributeRepository $magiceffectattributeRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->magiceffectattributeRepository = $magiceffectattributeRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }


    #[Route('/magiceffectattribute/view', name: 'magiceffectattribute_view', defaults: ['title' => 'View Magic Effect Attribute'])]
    public function index(MagicEffectAttributeRepository $magiceffectattributeRepository, Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->magiceffectattributeRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('magic_effect_attribute/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/magiceffectattribute/create', name: 'magiceffectattribute_create', defaults: ['title' => 'Create Magic Effect Attribute'])]
    public function create(Request $request, string $title): Response
    {
        $magiceffectattribute = new MagicEffectAttribute();

        $form = $this->createForm(MagicEffectAttributeType::class, $magiceffectattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffectattribute);
            $em->flush();

            return $this->redirectToRoute('magiceffectattribute_view');
        }
        return $this->render('magic_effect_attribute/create.html.twig', ['form' => $form->createView(),'magiceffectattribute' => $magiceffectattribute,'title' => $title]);

    }

    #[Route('/magiceffectattribute/edit/{id}', name: 'magiceffectattribute_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Magic Effect Attribute'])]
    public function edit(int $id, Request $request, string $title): Response
    {
        $magiceffectattribute = $this->magiceffectattributeRepository
            ->find($id);

        $form = $this->createForm(MagicEffectAttributeType::class, $magiceffectattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffectattribute);
            $em->flush();

            return $this->redirectToRoute('magiceffectattribute_view');
        }

        return $this->render('magic_effect_attribute/edit.html.twig', ['magiceffectattribute' => $magiceffectattribute,'form' => $form->createView(),'title' => $title]);
    }
}
