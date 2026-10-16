<?php

namespace App\Controller;

use App\Entity\Gamebook;
use App\Entity\GamebookPermission;
use App\Form\GamebookType;
use App\Form\GamebookLicenseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\GamebookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class GamebookController extends AbstractController
{

    private $security;
    private $gamebookRepository;
    private $paginator;
    private $doctrine;
    private $validator;

    public function __construct(Security $security, GamebookRepository $gamebookRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
       $this->security = $security;
       $this->gamebookRepository = $gamebookRepository;
       $this->paginator = $paginator;
       $this->validator = $validator;
       $this->doctrine = $doctrine;
    }

    #[Route('/gamebook/view', name: 'gamebook_view', defaults: ['title' => 'View Gamebook'])]
    public function index(Request $request, string $title): Response
    {
        $q = $request->query->get('q');
        $user = $this->security->getUser();
        $queryBuilder = $this->gamebookRepository->getWithSearchQueryBuilderView($q, $user->getId());

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('gamebook/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }

    #[Route('/gamebook/create', name: 'gamebook_create', defaults: ['title' => 'Create Gamebook'])]
    public function create(Request $request, string $title): Response
    {
        $gamebook = new Gamebook();

        $form = $this->createForm(GamebookType::class, $gamebook);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($gamebook);

            // Grant the creator access to their own gamebook, since only an
            // admin can otherwise assign a GamebookPermission after the fact.
            $gamebookPermission = new GamebookPermission();
            $gamebookPermission->setGamebook($gamebook);
            $gamebookPermission->setUser($this->security->getUser());
            $em->persist($gamebookPermission);

            $em->flush();

            return $this->redirectToRoute('gamebook_view');
        }
        return $this->render('gamebook/create.html.twig', ['form' => $form->createView(),'gamebook' => $gamebook,'title' => $title]);

    }

    #[Route('/gamebook/license', name: 'gamebook_license', defaults: ['title' => 'Add License'])]
    public function license(Request $request, string $title): Response
    {
        $form = $this->createForm(GamebookLicenseType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $user = $this->security->getUser();

            $gamebook = $this->gamebookRepository->findOneBy([
                'name' => $data['name'],
                'license' => $data['license'],
            ]);

            if ($gamebook !== null) {
                $em = $this->doctrine->getManager();
                $existing = $em->getRepository(GamebookPermission::class)->findOneBy([
                    'gamebook' => $gamebook,
                    'user' => $user,
                ]);

                if ($existing === null) {
                    $gamebookPermission = new GamebookPermission();
                    $gamebookPermission->setGamebook($gamebook);
                    $gamebookPermission->setUser($user);
                    $em->persist($gamebookPermission);
                    $em->flush();
                }

                $this->addFlash('success', 'You now have access to "' . $gamebook->getName() . '".');
            } else {
                $this->addFlash('failure', 'No gamebook matches that name and license.');
            }

            return $this->redirectToRoute('gamebook_view');
        }

        return $this->render('gamebook/license.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    #[Route('/gamebook/edit/{id}', name: 'gamebook_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Gamebook'])]
    public function edit(int $id, Request $request, string $title): Response
    {
        $gamebook = $this->gamebookRepository
            ->find($id);


        $form = $this->createForm(GamebookType::class, $gamebook);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($gamebook);
            $em->flush();

            return $this->redirectToRoute('gamebook_view');
        }

        return $this->render('gamebook/edit.html.twig', ['gamebook' => $gamebook,'form' => $form->createView(),'title' => $title]);
    }
}
