<?php

namespace App\Controller;

use App\Entity\ParagraphActionSpell;
use App\Form\ParagraphActionSpellType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphActionSpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphActionSpellController extends AbstractController
{

    private $paragraphactionspellRepository;
    private $paginator;
    private $doctrine;
    private $validator;

    public function __construct(ParagraphActionSpellRepository $paragraphactionspellRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphactionspellRepository = $paragraphactionspellRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/paragraphactionspell/view/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionspell_view', defaults: ['title' => 'View Paragraph Action Spell'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphactionspellRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_action_spell/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'paragraphaction' => $paragraphaction
        ]);
    }

    #[Route('/paragraphactionspell/create/{gamebook}/{paragraph}/{paragraphaction}', name: 'paragraphactionspell_create', defaults: ['title' => 'Create Paragraph Action Spell'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphaction): Response
    {
        $paragraphactionspell = new ParagraphActionSpell();

        $form = $this->createForm(ParagraphActionSpellType::class, $paragraphactionspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionspell);
            $em->flush();

            return $this->redirectToRoute('paragraphactionspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }
        return $this->render('paragraph_action_spell/create.html.twig', ['form' => $form->createView(),'paragraphactionspell' => $paragraphactionspell,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);

    }

    #[Route('/paragraphactionspell/edit/{gamebook}/{paragraph}/{paragraphaction}/{id}', name: 'paragraphactionspell_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Action Spell'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraphaction, int $paragraph): Response
    {
        $paragraphactionspell = $this->paragraphactionspellRepository
            ->find($id);

        $form = $this->createForm(ParagraphActionSpellType::class, $paragraphactionspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphactionspell);
            $em->flush();

            return $this->redirectToRoute('paragraphactionspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
        }

        return $this->render('paragraph_action_spell/edit.html.twig', ['paragraphactionspell' => $paragraphactionspell,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphaction' => $paragraphaction]);
    }
}
