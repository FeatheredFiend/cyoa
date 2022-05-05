<?php

namespace App\Controller;

use App\Entity\EquipmentEffectAttribute;
use App\Form\EquipmentEffectAttributeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EquipmentEffectAttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EquipmentEffectAttributeController extends AbstractController
{
    #[Route('/equipmenteffectattribute/view', name: 'equipmenteffectattribute_view', defaults: ['title' => 'View Equipment Effect Attribute'])]
    public function index(EquipmentEffectAttributeRepository $equipmenteffectattributeRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $equipmenteffectattributeRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('equipment_effect_attribute/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/equipmenteffectattribute/create', name: 'equipmenteffectattribute_create', defaults: ['title' => 'Create Equipment Effect Attribute'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffectattribute = new EquipmentEffectAttribute();

        $form = $this->createForm(EquipmentEffectAttributeType::class, $equipmenteffectattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffectattribute);
            $em->flush();

            return $this->redirectToRoute('equipmenteffectattribute_view');
        }
        return $this->render('equipment_effect_attribute/create.html.twig', ['form' => $form->createView(),'equipmenteffectattribute' => $equipmenteffectattribute,'title' => $title]);

    }

    #[Route('/equipmenteffectattribute/edit/{id}', name: 'equipmenteffectattribute_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Equipment Effect Attribute'])]
    public function edit(int $id, EquipmentEffectAttributeRepository $equipmenteffectattributeRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffectattribute = $equipmenteffectattributeRepository
            ->find($id);

        $equipmenteffectattribute->getName();


        $form = $this->createForm(EquipmentEffectAttributeType::class, $equipmenteffectattribute);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffectattribute);
            $em->flush();

            return $this->redirectToRoute('equipmenteffectattribute_view');
        }

        return $this->render('equipment_effect_attribute/edit.html.twig', ['equipmenteffectattribute' => $equipmenteffectattribute,'form' => $form->createView(),'title' => $title]);
    }
}
