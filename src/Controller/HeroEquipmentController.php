<?php

namespace App\Controller;

use App\Entity\HeroEquipment;
use App\Form\HeroEquipmentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\HeroEquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class HeroEquipmentController extends AbstractController
{
    #[Route('/heroequipment/view', name: 'heroequipment_view', defaults: ['title' => 'View Hero Equipment'])]
    public function index(HeroEquipmentRepository $heroequipmentRepository, Request $request, PaginatorInterface $paginator, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $heroequipmentRepository->getWithSearchQueryBuilderView($q);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('hero_equipment/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/heroequipment/create', name: 'heroequipment_create', defaults: ['title' => 'Create Hero Equipment'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, ManagerRegistry $doctrine): Response
    {
        $heroequipment = new HeroEquipment();

        $form = $this->createForm(HeroEquipmentType::class, $heroequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($heroequipment);
            $em->flush();

            return $this->redirectToRoute('heroequipment_view');
        }
        return $this->render('hero_equipment/create.html.twig', ['form' => $form->createView(),'heroequipment' => $heroequipment,'title' => $title]);

    }

    #[Route('/heroequipment/edit/{id}', name: 'heroequipment_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Hero Equipment'])]
    public function edit(int $id, HeroEquipmentRepository $heroequipmentRepository, Request $request,string $title, ManagerRegistry $doctrine): Response
    {
        $heroequipment = $heroequipmentRepository
            ->find($id);


        $form = $this->createForm(HeroEquipmentType::class, $heroequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($heroequipment);
            $em->flush();

            return $this->redirectToRoute('heroequipment_view');
        }

        return $this->render('hero_equipment/edit.html.twig', ['heroequipment' => $heroequipment,'form' => $form->createView(),'title' => $title]);
    }
}
