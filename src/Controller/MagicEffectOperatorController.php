<?php

namespace App\Controller;

use App\Entity\MagicEffectOperator;
use App\Form\MagicEffectOperatorType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MagicEffectOperatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MagicEffectOperatorController extends AbstractController
{
    private $magiceffectoperatorRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(MagicEffectOperatorRepository $magiceffectoperatorRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->magiceffectoperatorRepository = $magiceffectoperatorRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/magiceffectoperator/view', name: 'magiceffectoperator_view', defaults: ['title' => 'View Magic Effect Operator'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->magiceffectoperatorRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('magic_effect_operator/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/magiceffectoperator/create', name: 'magiceffectoperator_create', defaults: ['title' => 'Create Magic Effect Operator'])]
    public function create(Request $request, string $title): Response
    {
        $magiceffectoperator = new MagicEffectOperator();

        $form = $this->createForm(MagicEffectOperatorType::class, $magiceffectoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffectoperator);
            $em->flush();

            return $this->redirectToRoute('magiceffectoperator_view');
        }
        return $this->render('magic_effect_operator/create.html.twig', ['form' => $form->createView(),'magiceffectoperator' => $magiceffectoperator,'title' => $title]);

    }

    #[Route('/magiceffectoperator/edit/{id}', name: 'magiceffectoperator_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Magic Effect Operator'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $magiceffectoperator = $this->magiceffectoperatorRepository
            ->find($id);


        $form = $this->createForm(MagicEffectOperatorType::class, $magiceffectoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffectoperator);
            $em->flush();

            return $this->redirectToRoute('magiceffectoperator_view');
        }

        return $this->render('magic_effect_operator/edit.html.twig', ['magiceffectoperator' => $magiceffectoperator,'form' => $form->createView(),'title' => $title]);
    }
}
