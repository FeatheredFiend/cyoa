<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EquipmentController extends AbstractController
{
    #[Route('/equipment/view', name: 'equipment_view', defaults: ['title' => 'View Equipment'])]
    public function index(EquipmentRepository $equipmentRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $equipmentRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('equipment/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/equipment/create', name: 'equipment_create', defaults: ['title' => 'Create Equipment'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $equipment = new Equipment();

        $form = $this->createForm(EquipmentType::class, $equipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->redirectToRoute('equipment_view');
        }
        return $this->render('equipment/create.html.twig', ['form' => $form->createView(),'equipment' => $equipment,'title' => $title]);

    }

    #[Route('/equipment/edit/{id}', name: 'equipment_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Equipment'])]
    public function edit(int $id, EquipmentRepository $equipmentRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $equipment = $equipmentRepository
            ->find($id);

        $equipment->getName();


        $form = $this->createForm(EquipmentType::class, $equipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->redirectToRoute('equipment_view');
        }

        return $this->render('equipment/edit.html.twig', ['equipment' => $equipment,'form' => $form->createView(),'title' => $title]);
    }
}
