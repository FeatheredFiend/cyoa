<?php

namespace App\Controller;

use App\Entity\ParagraphEquipment;
use App\Form\ParagraphEquipmentType;
use App\Service\UseEquipment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphEquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ParagraphEquipmentController extends AbstractController
{
    private $useEquipment;
    private $paragraphequipmentRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(ParagraphEquipmentRepository $paragraphequipmentRepository, UseEquipment $useEquipment, PaginatorInterface $paginator, ValidatorInterface $validator, ManagerRegistry $doctrine)
    {
        $this->useEquipment = $useEquipment;
        $this->paragraphequipmentRepository = $paragraphequipmentRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;

    }


    #[Route('/paragraphequipment/view/{gamebook}/{paragraph}', name: 'paragraphequipment_view', defaults: ['title' => 'View Paragraph Equipment'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphequipmentRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_equipment/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/paragraphequipment/create/{gamebook}/{paragraph}', name: 'paragraphequipment_create', defaults: ['title' => 'Create Paragraph Equipment'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphequipment = new ParagraphEquipment();

        $form = $this->createForm(ParagraphEquipmentType::class, $paragraphequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphequipment);
            $em->flush();

            return $this->redirectToRoute('paragraphequipment_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('paragraph_equipment/create.html.twig', ['form' => $form->createView(),'paragraphequipment' => $paragraphequipment,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph]);

    }

    #[Route('/paragraphequipment/edit/{gamebook}/{paragraph}/{id}', name: 'paragraphequipment_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Equipment'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphequipment = $this->paragraphequipmentRepository
            ->find($id);

        $form = $this->createForm(ParagraphEquipmentType::class, $paragraphequipment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphequipment);
            $em->flush();

            return $this->redirectToRoute('paragraphequipment_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('paragraph_equipment/edit.html.twig', ['paragraphequipment' => $paragraphequipment,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph]);
    }

    #[Route('/run-paragraph-equipment', name: 'run_paragraph_equipment', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listParagraphEquipmentAction(Request $request )
    {
        // Get Entity manager and repository
        $em = $this->doctrine->getManager();
        
        $equipment = $request->query->get("equipment");
        $adventure = $request->query->get("adventure");
        $quantity = $request->query->get("quantity");
        $category = $request->query->get("category");
      
        if ($category == "Add") {
            $this->useEquipment->gainEquipment($adventure, $equipment, $quantity);
        } else {
            $this->useEquipment->useEquipment($adventure, $equipment, $quantity);
        }


        $results = [
            'adventure' => $adventure,
            'equipment' => $equipment,
            'quantity' => $quantity
        ];
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($results);

    }

}
