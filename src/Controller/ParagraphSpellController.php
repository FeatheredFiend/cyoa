<?php

namespace App\Controller;

use App\Entity\ParagraphSpell;
use App\Form\ParagraphSpellType;
use App\Service\UseSpell;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ParagraphSpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ParagraphSpellController extends AbstractController
{
    private $useSpell; 
    private $paragraphspellRepository; 
    private $paginator; 
    private $doctrine;
    private $validator;  


    public function __construct(ParagraphSpellRepository $paragraphspellRepository, UseSpell $useSpell, PaginatorInterface $paginator, ValidatorInterface $validator, ManagerRegistry $doctrine)
    {
        $this->useSpell = $useSpell;
        $this->paragraphspellRepository = $paragraphspellRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;  

    }


    #[Route('/paragraphspell/view/{gamebook}/{paragraph}', name: 'paragraphspell_view', defaults: ['title' => 'View Paragraph Spell'])]
    public function index(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->paragraphspellRepository->getWithSearchQueryBuilderViewParagraph($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('paragraph_spell/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/paragraphspell/create/{gamebook}/{paragraph}', name: 'paragraphspell_create', defaults: ['title' => 'Create Paragraph Spell'])]
    public function create(Request $request, string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphspell = new ParagraphSpell();

        $form = $this->createForm(ParagraphSpellType::class, $paragraphspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphspell);
            $em->flush();

            return $this->redirectToRoute('paragraphspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('paragraph_spell/create.html.twig', ['form' => $form->createView(),'paragraphspell' => $paragraphspell,'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph]);

    }

    #[Route('/paragraphspell/edit/{gamebook}/{paragraph}/{id}', name: 'paragraphspell_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Paragraph Spell'])]
    public function edit(int $id, Request $request,string $title, string $gamebook, int $paragraph): Response
    {
        $paragraphspell = $this->paragraphspellRepository
            ->find($id);

        $form = $this->createForm(ParagraphSpellType::class, $paragraphspell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($paragraphspell);
            $em->flush();

            return $this->redirectToRoute('paragraphspell_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('paragraph_spell/edit.html.twig', ['paragraphspell' => $paragraphspell,'form' => $form->createView(),'title' => $title, 'gamebook' => $gamebook, 'paragraph' => $paragraph]);
    }

    #[Route('/run-paragraph-spell', name: 'run_paragraph_spell', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listParagraphSpellAction(Request $request )
    {
        // Get Entity manager and repository
        $em = $this->doctrine->getManager();
        
        $spell = $request->query->get("spell");
        $adventure = $request->query->get("adventure");
        $quantity = $request->query->get("quantity");
        $category = $request->query->get("category");
      
        if ($category == "Add") {
            $this->useSpell->gainSpell($adventure, $spell, $quantity);            
        } else {
            $this->useSpell->useSpell($adventure, $spell, $quantity);
        }


        $results = [
            'adventure' => $adventure,
            'spell' => $spell,
            'quantity' => $quantity
        ];
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($results);

    }

}
