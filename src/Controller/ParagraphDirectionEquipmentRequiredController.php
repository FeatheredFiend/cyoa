<?php

namespace App\Controller;

use App\Entity\ParagraphDirectionEquipmentRequired;
use App\Form\ParagraphDirectionEquipmentRequiredType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphDirectionEquipmentRequiredRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphDirectionEquipmentRequiredController extends AbstractController
{
    #[Route('/paragraphdirectionequipmentrequired/view/{gamebook}/{paragraph}/{paragraphdirection}', name: 'paragraphdirectionequipmentrequired_view', defaults: ['title' => 'View Paragraph Direction Equipment Required'])]
    public function index(ParagraphDirectionEquipmentRequiredRepository $paragraphdirectionequipmentrequiredRepository, Request $request, PaginatorInterface $paginator, string $title, string $gamebook, int $paragraph, int $paragraphdirection): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $paragraphdirectionequipmentrequiredRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_direction_equipment_required/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'paragraphdirection' => $paragraphdirection
        ]);
    }

    #[Route('/paragraphdirectionequipmentrequired/create/{gamebook}/{paragraph}/{paragraphdirection}', name: 'paragraphdirectionequipmentrequired_create', defaults: ['title' => 'Create Paragraph Direction Equipment Required'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, string $gamebook, int $paragraph, int $paragraphdirection, ManagerRegistry $doctrine): Response
    {
        $paragraphdirectionequipmentrequired = new ParagraphDirectionEquipmentRequired();

        $form = $this->createForm(ParagraphDirectionEquipmentRequiredType::class, $paragraphdirectionequipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphdirectionequipmentrequired);
            $em->flush();

            return $this->redirectToRoute('paragraphdirectionequipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);
        }
        return $this->render('paragraph_direction_equipment_required/create.html.twig', ['form' => $form->createView(),'paragraphdirectionequipmentrequired' => $paragraphdirectionequipmentrequired,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);

    }

    #[Route('/paragraphdirectionequipmentrequired/edit/{gamebook}/{id}', name: 'paragraphdirectionequipmentrequired_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Direction Equipment Required'])]
    public function edit(int $id, ParagraphDirectionEquipmentRequiredRepository $paragraphdirectionequipmentrequiredRepository, Request $request,string $title, string $gamebook, ManagerRegistry $doctrine): Response
    {
        $paragraphdirectionequipmentrequired = $paragraphdirectionequipmentrequiredRepository
            ->find($id);

        $form = $this->createForm(ParagraphDirectionEquipmentRequiredType::class, $paragraphdirectionequipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($paragraphdirectionequipmentrequired);
            $em->flush();

            return $this->redirectToRoute('paragraphdirectionequipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraphdirectionequipmentrequired->getParagraphdirection()]);
        }

        return $this->render('paragraph_direction_equipment_required/edit.html.twig', ['paragraphdirectionequipmentrequired' => $paragraphdirectionequipmentrequired,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraphdirectionequipmentrequired->getParagraphdirection()]);
    }
}
