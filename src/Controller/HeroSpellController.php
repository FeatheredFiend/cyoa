<?php

namespace App\Controller;

use App\Entity\HeroSpell;
use App\Form\HeroSpellType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\HeroSpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HeroSpellController extends AbstractController
{

    private $herospellRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;   

    public function __construct(HeroSpellRepository $herospellRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->herospellRepository = $herospellRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;       
    }

    #[Route('/herospell/view', name: 'herospell_view', defaults: ['title' => 'View Hero Spell'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->herospellRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('hero_spell/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/herospell/create', name: 'herospell_create', defaults: ['title' => 'Create Hero Spell'])]
    public function create(Request $request, string $title): Response
    {
        $herospell = new HeroSpell();

        $form = $this->createForm(HeroSpellType::class, $herospell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($herospell);
            $em->flush();

            return $this->redirectToRoute('herospell_view');
        }
        return $this->render('hero_spell/create.html.twig', ['form' => $form->createView(),'herospell' => $herospell,'title' => $title]);

    }

    #[Route('/herospell/edit/{id}', name: 'herospell_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Hero Spell'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $herospell = $this->herospellRepository
            ->find($id);


        $form = $this->createForm(HeroSpellType::class, $herospell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($herospell);
            $em->flush();

            return $this->redirectToRoute('herospell_view');
        }

        return $this->render('hero_spell/edit.html.twig', ['herospell' => $herospell,'form' => $form->createView(),'title' => $title]);
    }

    #[Route('/get-hero-spell', name: 'get_hero_spell', defaults:['return' => 'JsonResponse', 'param' => 'Request $request'])]
    public function listHeroSpellAction(Request $request )
    {
        // Get Entity manager and repository

        $adventure = $request->query->get("adventure");

        $em = $this->doctrine->getManager();
        $herospellsRepository = $em->getRepository("App\Entity\HeroSpell");
        
        // Search the departments that belongs to the building with the given id as GET parameter "buildingid"
        $herospells = $herospellsRepository->createQueryBuilder("he")
            ->leftJoin("he.hero", "h")
            ->leftJoin("h.adventure", "a")
            ->where("a.id = :adventure")
            ->setParameter("adventure", $adventure)
            ->getQuery()
            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
        foreach($herospells as $herospell){
            $responseArray[] = array(
                "herospell" => $herospell->getId(),
                "id" => $herospell->getSpell()->getId(),
                "name" => $herospell->getSpell()->getName(),
                "quantity" => $herospell->getQuantity()
            );
        }
        
        
        // Return array with structure of the buildings of the providen organisation id
        return new JsonResponse($responseArray);

    }
}
