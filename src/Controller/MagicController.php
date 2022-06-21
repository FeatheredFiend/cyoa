<?php

namespace App\Controller;

use App\Entity\Magic;
use App\Form\MagicType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MagicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class MagicController extends AbstractController
{
    private $magicRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(MagicRepository $magicRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->magicRepository = $magicRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }


    #[Route('/magic/view/{gamebook}/{paragraph}', name: 'magic_view', defaults: ['title' => 'View Magic'])]
    public function index(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->magicRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('magic/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph

        ]);
    }

    #[Route('/magic/create/{gamebook}/{paragraph}', name: 'magic_create', defaults: ['title' => 'Create Magic'])]
    public function create(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $magic = new Magic();

        $form = $this->createForm(MagicType::class, $magic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magic);
            $em->flush();

            return $this->redirectToRoute('magic_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('magic/create.html.twig', [
            'form' => $form->createView(),
            'magic' => $magic,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/magic/edit/{gamebook}/{paragraph}/{id}', name: 'magic_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Magic'])]
    public function edit(int $id, Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $magic = $this->magicRepository
            ->find($id);

        $form = $this->createForm(MagicType::class, $magic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($magic);
            $em->flush();

            return $this->redirectToRoute('magic_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('magic/edit.html.twig', [
            'form' => $form->createView(),
            'magic' => $magic,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }
}
