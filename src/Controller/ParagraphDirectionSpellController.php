<?php

namespace App\Controller;

use App\Entity\ParagraphDirectionSpell;
use App\Form\ParagraphDirectionSpellType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphDirectionSpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class ParagraphDirectionSpellController extends AbstractController
{

    private $paragraphdirectionspellRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(ParagraphDirectionSpellRepository $paragraphdirectionspellRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->paragraphdirectionspellRepository = $paragraphdirectionspellRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/paragraphdirectionspell/view/{gamebook}/{paragraph}/{paragraphdirection}', name: 'paragraphdirectionspell_view', defaults: ['title' => 'View Paragraph Direction Spell'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphdirection): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphdirectionspellRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_direction_spell/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph,
            'paragraphdirection' => $paragraphdirection
        ]);
    }

    #[Route('/paragraphdirectionspell/create/{gamebook}/{paragraph}/{paragraphdirection}', name: 'paragraphdirectionspell_create', defaults: ['title' => 'Create Paragraph Direction Spell'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph, int $paragraphdirection): Response
    {
        $paragraphdirectionspell = new ParagraphDirectionSpell();

        $form = $this->createForm(ParagraphDirectionSpellType::class, $paragraphdirectionspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphdirectionspell);
            $em->flush();

            return $this->redirectToRoute('paragraphdirectionspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);
        }
        return $this->render('paragraph_direction_spell/create.html.twig', ['form' => $form->createView(),'paragraphdirectionspell' => $paragraphdirectionspell,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);

    }

    #[Route('/paragraphdirectionspell/edit/{gamebook}/{paragraph}/{paragraphdirection}/{id}', name: 'paragraphdirectionspell_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Direction Spell'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraphdirection, int $paragraph): Response
    {
        $paragraphdirectionspell = $this->paragraphdirectionspellRepository
            ->find($id);

        $form = $this->createForm(ParagraphDirectionSpellType::class, $paragraphdirectionspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphdirectionspell);
            $em->flush();

            return $this->redirectToRoute('paragraphdirectionspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);
        }

        return $this->render('paragraph_direction_spell/edit.html.twig', ['paragraphdirectionspell' => $paragraphdirectionspell,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph, 'paragraphdirection' => $paragraphdirection]);
    }
}
