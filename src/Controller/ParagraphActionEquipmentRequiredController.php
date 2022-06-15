<?php

namespace App\Controller;

use App\Entity\ParagraphActionEquipmentRequired;
use App\Form\ParagraphActionEquipmentRequiredType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionEquipmentRequiredRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionEquipmentRequiredController extends AbstractController
{

    private $paragraphactionequipmentrequiredRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(ParagraphActionEquipmentRequiredRepository $paragraphactionequipmentrequiredRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactionequipmentrequiredRepository = $paragraphactionequipmentrequiredRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/paragraphactionequipmentrequired/view/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionequipmentrequired_view', defaults: ['title' => 'View Paragraph Action Equipment Required'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactionequipmentrequiredRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_equipment_required/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'paragraphaction' => $paragraphaction
        ]);
    }

    #[Route('/paragraphactionequipmentrequired/create/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionequipmentrequired_create', defaults: ['title' => 'Create Paragraph Action Equipment Required'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $paragraphactionequipmentrequired = new ParagraphActionEquipmentRequired();

        $form = $this->createForm(ParagraphActionEquipmentRequiredType::class, $paragraphactionequipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionequipmentrequired);
            $em->flush();

            return $this->redirectToRoute('paragraphactionequipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }
        return $this->render('paragraph_action_equipment_required/create.html.twig', ['form' => $form->createView(),'paragraphactionequipmentrequired' => $paragraphactionequipmentrequired,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);

    }

    #[Route('/paragraphactionequipmentrequired/edit/{gamebook}/{paragraph}/{paragraphaction}/{id}', name: 'paragraphactionequipmentrequired_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Equipment Required'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraphaction, int $paragraph): Response
    {
        $paragraphactionequipmentrequired = $this->paragraphactionequipmentrequiredRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionEquipmentRequiredType::class, $paragraphactionequipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionequipmentrequired);
            $em->flush();

            return $this->redirectToRoute('paragraphactionequipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }

        return $this->render('paragraph_action_equipment_required/edit.html.twig', ['paragraphactionequipmentrequired' => $paragraphactionequipmentrequired,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
    }
}
