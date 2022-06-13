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
use Symfony\Component\HttpFoundation\JsonResponse;

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
            5/*limit per page*/
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

    #[Route('/get-hero-equipment', name: 'get_hero_equipment', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listHeroEquipmentAction(Request $request, ManagerRegistry $doctrine )
    {
        // Get Entity manager and repository

        $adventure = $request->query->get("adventure");

        $em = $doctrine->getManager();
        $heroequipmentsRepository = $em->getRepository("App\Entity\HeroEquipment");
        
        // Search the departments that belongs to the building with the given id as GET parameter "buildingid"
        $heroequipments = $heroequipmentsRepository->createQueryBuilder("he")
            ->leftJoin("he.hero", "h")
            ->leftJoin("h.adventure", "a")
            ->where("a.id = :adventure")
            ->setParameter("adventure", $adventure)
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($heroequipments as $heroequipment){
            $responseArray[] = array(
                "heroequipment" => $heroequipment->getId(),
                "id" => $heroequipment->getEquipment()->getId(),
                "name" => $heroequipment->getEquipment()->getName(),
                "quantity" => $heroequipment->getQuantity()
            );
        }
        
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($responseArray);

    }
}
