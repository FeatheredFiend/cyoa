<?php

namespace App\Controller;

use App\Entity\EquipmentEffect;
use App\Form\EquipmentEffectType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EquipmentEffectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EquipmentEffectController extends AbstractController
{
    #[Route('/equipmenteffect/view', name: 'equipmenteffect_view', defaults: ['title' => 'View Equipment Effect'])]
    public function index(EquipmentEffectRepository $equipmenteffectRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $equipmenteffectRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('equipment_effect/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/equipmenteffect/create', name: 'equipmenteffect_create', defaults: ['title' => 'Create Equipment Effect'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffect = new EquipmentEffect();

        $form = $this->createForm(EquipmentEffectType::class, $equipmenteffect);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffect);
            $em->flush();

            return $this->redirectToRoute('equipmenteffect_view');
        }
        return $this->render('equipment_effect/create.html.twig', ['form' => $form->createView(),'equipmenteffect' => $equipmenteffect,'title' => $title]);

    }

    #[Route('/equipmenteffect/edit/{id}', name: 'equipmenteffect_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Equipment Effect'])]
    public function edit(int $id, EquipmentEffectRepository $equipmenteffectRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $equipmenteffect = $equipmenteffectRepository
            ->find($id);

        $equipmenteffect->getName();


        $form = $this->createForm(EquipmentEffectType::class, $equipmenteffect);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmenteffect);
            $em->flush();

            return $this->redirectToRoute('equipmenteffect_view');
        }

        return $this->render('equipment_effect/edit.html.twig', ['equipmenteffect' => $equipmenteffect,'form' => $form->createView(),'title' => $title]);
    }
}
