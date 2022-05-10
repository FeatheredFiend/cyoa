<?php

namespace App\Controller;

use App\Entity\EquipmentRequired;
use App\Form\EquipmentRequiredType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EquipmentRequiredRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class EquipmentRequiredController extends AbstractController
{
    #[Route('/equipmentrequired/view/{gamebook}/{paragraph}', name: 'equipmentrequired_view', defaults: ['title' => 'View Equipment Required'])]
    public function index(EquipmentRequiredRepository $equipmentrequiredRepository, Request $request, PaginatorInterface $paginator, string $title, string $gamebook, string $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $equipmentrequiredRepository->getWithSearchQueryBuilderView($q, $gamebook, $paragraph);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('equipment_required/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/equipmentrequired/create/{gamebook}/{paragraph}', name: 'equipmentrequired_create', defaults: ['title' => 'Create Equipment Required'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, string $gamebook, string $paragraph, ManagerRegistry $doctrine): Response
    {
        $equipmentrequired = new EquipmentRequired();

        $form = $this->createForm(EquipmentRequiredType::class, $equipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmentrequired);
            $em->flush();

            return $this->redirectToRoute('equipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('equipment_required/create.html.twig', [
            'form' => $form->createView(),
            'equipmentrequired' => $equipmentrequired,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/equipmentrequired/edit/{gamebook}/{paragraph}/{id}', name: 'equipmentrequired_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Equipment Required'])]
    public function edit(int $id, EquipmentRequiredRepository $equipmentrequiredRepository, Request $request,string $title, string $gamebook, string $paragraph, ManagerRegistry $doctrine): Response
    {
        $equipmentrequired = $equipmentrequiredRepository
            ->find($id);

        $form = $this->createForm(EquipmentRequiredType::class, $equipmentrequired);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($equipmentrequired);
            $em->flush();

            return $this->redirectToRoute('equipmentrequired_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('equipment_required/edit.html.twig', [
            'equipmentrequired' => $equipmentrequired,
            'form' => $form->createView(),
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }
}
