<?php

namespace App\Controller;

use App\Entity\GamebookPermission;
use App\Form\GamebookPermissionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\GamebookPermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class GamebookPermissionController extends AbstractController
{

    private $gamebookpermissionRepository;
    private $paginator;
    private $doctrine;
    private $validator;


    public function __construct(GamebookPermissionRepository $gamebookpermissionRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->gamebookpermissionRepository = $gamebookpermissionRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    #[Route('/gamebookpermission/view', name: 'gamebookpermission_view', defaults: ['title' => 'View Gamebook Permission'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->gamebookpermissionRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('gamebook_permission/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/gamebookpermission/create', name: 'gamebookpermission_create', defaults: ['title' => 'Create Gamebook Permission'])]
    public function create(Request $request, string $title): Response
    {
        $gamebookpermission = new GamebookPermission();

        $form = $this->createForm(GamebookPermissionType::class, $gamebookpermission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gamebook = $form['gamebook']->getData();
            $license = $form['license']->getData();

            $gamebookLicense = $this->getGamebookLicense($gamebook);

            if ($license == $gamebookLicense) {
            // Save
                $em = $this->doctrine->getManager();
                $em->persist($gamebookpermission);
                $em->flush();
            }
            



            return $this->redirectToRoute('gamebookpermission_view');
        }
        return $this->render('gamebook_permission/create.html.twig', ['form' => $form->createView(),'gamebookpermission' => $gamebookpermission,'title' => $title]);

    }

    #[Route('/gamebookpermission/edit/{id}', name: 'gamebookpermission_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Gamebook Permission'])]
    public function edit(int $id, Request $request,string $title): Response
    {
        $gamebookpermission = $this->gamebookpermissionRepository
            ->find($id);


        $form = $this->createForm(GamebookPermissionType::class, $gamebookpermission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($gamebookpermission);
            $em->flush();

            return $this->redirectToRoute('gamebookpermission_view');
        }

        return $this->render('gamebook_permission/edit.html.twig', ['gamebookpermission' => $gamebookpermission,'form' => $form->createView(),'title' => $title]);
    }

    public function getGamebookLicense($gamebook)
    {
        $em = $this->doctrine->getManager();
        $gamebooksRepository = $em->getRepository("App\Entity\Gamebook");
        
        // Search the buildings that belongs to the organisation with the given id as GET parameter "organisationid"
        $gamebookLicense = $gamebooksRepository->createQueryBuilder("g")
            ->select('g.license')
            ->andWhere('g.id = :gamebook')
            ->setParameter('gamebook', $gamebook)
            ->getQuery()
            ->getSingleScalarResult();

        return $gamebookLicense;
    }
}
