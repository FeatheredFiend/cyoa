<?php

namespace App\Controller;

use App\Entity\MagicEffect;
use App\Form\MagicEffectType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MagicEffectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MagicEffectController extends AbstractController
{

    private $magiceffectRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(MagicEffectRepository $magiceffectRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->magiceffectRepository = $magiceffectRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/magiceffect/view', name: 'magiceffect_view', defaults: ['title' => 'View Magic Effect'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->magiceffectRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('magic_effect/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/magiceffect/create', name: 'magiceffect_create', defaults: ['title' => 'Create Magic Effect'])]
    public function create(Request $request, string $title): Response
    {
        $magiceffect = new MagicEffect();

        $form = $this->createForm(MagicEffectType::class, $magiceffect);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffect);
            $em->flush();

            return $this->redirectToRoute('magiceffect_view');
        }
        return $this->render('magic_effect/create.html.twig', ['form' => $form->createView(),'magiceffect' => $magiceffect,'title' => $title]);

    }

    #[Route('/magiceffect/edit/{id}', name: 'magiceffect_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Magic Effect'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $magiceffect = $this->magiceffectRepository
            ->find($id);

        $form = $this->createForm(MagicEffectType::class, $magiceffect);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magiceffect);
            $em->flush();

            return $this->redirectToRoute('magiceffect_view');
        }

        return $this->render('magic_effect/edit.html.twig', ['magiceffect' => $magiceffect,'form' => $form->createView(),'title' => $title]);
    }
}
