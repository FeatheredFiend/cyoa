<?php

namespace App\Controller;

use App\Entity\MagicEquipment;
use App\Form\MagicEquipmentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MagicEquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MagicEquipmentController extends AbstractController
{

    private $magicequipmentRepository;
    private $paginator;
    private $doctrine;
    private $validator;

    public function __construct(MagicEquipmentRepository $magicequipmentRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->magicequipmentRepository = $magicequipmentRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/magicequipment/view', name: 'magicequipment_view', defaults: ['title' => 'View Magic Equipment'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->magicequipmentRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('magic_equipment/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/magicequipment/create', name: 'magicequipment_create', defaults: ['title' => 'Create Magic Equipment'])]
    public function create(Request $request, string $title): Response
    {
        $magicequipment = new MagicEquipment();

        $form = $this->createForm(MagicEquipmentType::class, $magicequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magicequipment);
            $em->flush();

            return $this->redirectToRoute('magicequipment_view');
        }
        return $this->render('magic_equipment/create.html.twig', ['form' => $form->createView(),'magicequipment' => $magicequipment,'title' => $title]);

    }

    #[Route('/magicequipment/edit/{id}', name: 'magicequipment_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Magic Equipment'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $magicequipment = $this->magicequipmentRepository
            ->find($id);


        $form = $this->createForm(MagicEquipmentType::class, $magicequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magicequipment);
            $em->flush();

            return $this->redirectToRoute('magicequipment_view');
        }

        return $this->render('magic_equipment/edit.html.twig', ['magicequipment' => $magicequipment,'form' => $form->createView(),'title' => $title]);
    }

    #[Route('/get-magic-equipment', name: 'get_magic_equipment', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listMagicEquipmentAction(Request $request )
    {
        // Get Entity manager and repository

        $adventure = $request->query->get("adventure");

        $em = $this->doctrine->getManager();
        $magicequipmentsRepository = $em->getRepository("App\Entity\MagicEquipment");
        
        // Search the departments that belongs to the building with the given id as GET parameter "buildingid"
        $magicequipments = $magicequipmentsRepository->createQueryBuilder("he")
            ->leftJoin("he.magic", "h")
            ->leftJoin("h.adventure", "a")
            ->where("a.id = :adventure")
            ->setParameter("adventure", $adventure)
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($magicequipments as $magicequipment){
            $responseArray[] = array(
                "magicequipment" => $magicequipment->getId(),
                "id" => $magicequipment->getEquipment()->getId(),
                "name" => $magicequipment->getEquipment()->getName(),
                "quantity" => $magicequipment->getQuantity()
            );
        }
        
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($responseArray);

    }
}
