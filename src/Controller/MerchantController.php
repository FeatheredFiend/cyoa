<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Form\MerchantType;
use App\Service\BuyEquipment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MerchantController extends AbstractController
{
    #[Route('/merchant/view/{gamebook}/{paragraph}', name: 'merchant_view', defaults: ['title' => 'View Merchant'])]
    public function index(MerchantRepository $merchantRepository, Request $request, PaginatorInterface $paginator, string $title, string $gamebook, string $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $merchantRepository->getWithSearchQueryBuilderView($q, $paragraph);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('merchant/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph

        ]);
    }

    #[Route('/merchant/create/{gamebook}/{paragraph}', name: 'merchant_create', defaults: ['title' => 'Create Merchant'])]
    public function create(ValidatorInterface $validator, Request $request, string $title, string $gamebook, string $paragraph, ManagerRegistry $doctrine): Response
    {
        $merchant = new Merchant();

        $form = $this->createForm(MerchantType::class, $merchant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($merchant);
            $em->flush();

            return $this->redirectToRoute('merchant_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('merchant/create.html.twig', [
            'form' => $form->createView(),
            'merchant' => $merchant,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/merchant/edit/{gamebook}/{paragraph}/{id}', name: 'merchant_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Merchant'])]
    public function edit(int $id, MerchantRepository $merchantRepository, Request $request,string $title, string $gamebook, string $paragraph, ManagerRegistry $doctrine): Response
    {
        $merchant = $merchantRepository
            ->find($id);

        $form = $this->createForm(MerchantType::class, $merchant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $doctrine->getManager();
            $em->persist($merchant);
            $em->flush();

            return $this->redirectToRoute('merchant_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('merchant/edit.html.twig', [
            'form' => $form->createView(),
            'merchant' => $merchant,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/buyequipment/{adventure}/{paragraph}/{merchantinventory}/{equipment}/{quantity}', name: 'buy_equipment', defaults: ['title' => 'Buy Equipment'])]
    public function next(Request $request, string $title, int $adventure, int $paragraph, int $merchantinventory, int $equipment, int $quantity, BuyEquipment $buyEquipment): Response
    {
        $heroTreasure = $buyEquipment->getHeroTreasure($adventure);
        $equipmentCost = $buyEquipment->getEquipmentCost($equipment);

        if ($heroTreasure >= $equipmentCost) {
            $buyEquipment->buyEquipment($adventure, $equipment, $quantity);
            $buyEquipment->removeEquipment($merchantinventory);
            $this->addFlash("success", "You have brought this Equipment!");

            return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraph]);
        } else {

            $this->addFlash("failure", "You don't have enough Treasure to buy this!");

            return $this->redirectToRoute('adventure_play', ['adventure' => $adventure, 'paragraph' => $paragraph]);
        }


    }
}
