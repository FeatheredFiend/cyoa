<?php

namespace App\Controller;

use App\Entity\Spell;
use App\Form\SpellType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class SpellController extends AbstractController
{
    private $spellRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(SpellRepository $spellRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->spellRepository = $spellRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }


    #[Route('/spell/view/{gamebook}/{paragraph}', name: 'spell_view', defaults: ['title' => 'View Spell'])]
    public function index(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->spellRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('spell/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph

        ]);
    }

    #[Route('/spell/create/{gamebook}/{paragraph}', name: 'spell_create', defaults: ['title' => 'Create Spell'])]
    public function create(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $spell = new Spell();

        $form = $this->createForm(SpellType::class, $spell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($spell);
            $em->flush();

            return $this->redirectToRoute('spell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('spell/create.html.twig', [
            'form' => $form->createView(),
            'spell' => $spell,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/spell/edit/{gamebook}/{paragraph}/{id}', name: 'spell_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Spell'])]
    public function edit(int $id, Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $spell = $this->spellRepository
            ->find($id);

        $form = $this->createForm(SpellType::class, $spell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($spell);
            $em->flush();

            return $this->redirectToRoute('spell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('spell/edit.html.twig', [
            'form' => $form->createView(),
            'spell' => $spell,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }
}
