<?php

namespace App\Controller;

use App\Entity\EquipmentEffectOperator;
use App\Form\EquipmentEffectOperatorType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EquipmentEffectOperatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EquipmentEffectOperatorController extends AbstractController
{
    #[Route('/equipmenteffectoperator/view', name: 'equipmenteffectoperator_view', defaults: ['title' => 'View Equipment Effect Operator'])]
    public function index(EquipmentEffectOperatorRepository $equipmenteffectoperatorRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $equipmenteffectoperatorRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('equipment_effect_operator/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/equipmenteffectoperator/create', name: 'equipmenteffectoperator_create', defaults: ['title' => 'Create Equipment Effect Operator'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffectoperator = new EquipmentEffectOperator();

        $form = $this->createForm(EquipmentEffectOperatorType::class, $equipmenteffectoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffectoperator);
            $em->flush();

            return $this->redirectToRoute('equipmenteffectoperator_view');
        }
        return $this->render('equipment_effect_operator/create.html.twig', ['form' => $form->createView(),'equipmenteffectoperator' => $equipmenteffectoperator,'title' => $title]);

    }

    #[Route('/equipmenteffectoperator/edit/{id}', name: 'equipmenteffectoperator_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Equipment Effect Operator'])]
    public function edit(int $id, EquipmentEffectOperatorRepository $equipmenteffectoperatorRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffectoperator = $equipmenteffectoperatorRepository
            ->find($id);


        $form = $this->createForm(EquipmentEffectOperatorType::class, $equipmenteffectoperator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffectoperator);
            $em->flush();

            return $this->redirectToRoute('equipmenteffectoperator_view');
        }

        return $this->render('equipment_effect_operator/edit.html.twig', ['equipmenteffectoperator' => $equipmenteffectoperator,'form' => $form->createView(),'title' => $title]);
    }
}
