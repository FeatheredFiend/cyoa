<?php

namespace App\Controller;

use App\Entity\MerchantInventory;
use App\Form\MerchantInventoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MerchantInventoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MerchantInventoryController extends AbstractController
{
    #[Route('/merchantinventory/view/{gamebook}/{paragraph}/{merchant}', name: 'merchantinventory_view', defaults: ['title' => 'View Merchant Inventory'])]
    public function index(MerchantInventoryRepository $merchantinventoryRepository, Request $request, PaginatorInterface $paginator, string $title, string $gamebook, string $paragraph, string $merchant): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $merchantinventoryRepository->getWithSearchQueryBuilderView($q, $merchant);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('merchant_inventory/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'merchant' => $merchant

        ]);
    }

    #[Route('/merchantinventory/create/{gamebook}/{paragraph}/{merchant}', name: 'merchantinventory_create', defaults: ['title' => 'Create Merchant Inventory'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, string $gamebook, string $paragraph, string $merchant, ManagerRegistry $doctrine): Response
    {
        $merchantinventory = new MerchantInventory();

        $form = $this->createForm(MerchantInventoryType::class, $merchantinventory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($merchantinventory);
            $em->flush();

            return $this->redirectToRoute('merchantinventory_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'merchant' => $merchant]);
        }
        return $this->render('merchant_inventory/create.html.twig', [
            'form' => $form->createView(),
            'merchantinventory' => $merchantinventory,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'merchant' => $merchant
        ]);

    }

    #[Route('/merchantinventory/edit/{gamebook}/{paragraph}/{merchant}/{id}', name: 'merchantinventory_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Merchant Inventory'])]
    public function edit(int $id, MerchantInventoryRepository $merchantinventoryRepository, Request $request,string $title, string $gamebook, string $paragraph, string $merchant, ManagerRegistry $doctrine): Response
    {
        $merchantinventory = $merchantinventoryRepository
            ->find($id);

        $form = $this->createForm(MerchantInventoryType::class, $merchantinventory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($merchantinventory);
            $em->flush();

            return $this->redirectToRoute('merchantinventory_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'merchant' => $merchant]);
        }

        return $this->render('merchant_inventory/edit.html.twig', [
            'form' => $form->createView(),
            'merchantinventory' => $merchantinventory,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'merchant' => $merchant
        ]);
    }

}
