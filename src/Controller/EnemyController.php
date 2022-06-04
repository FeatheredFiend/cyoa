<?php

namespace App\Controller;

use App\Entity\Enemy;
use App\Form\EnemyType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EnemyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\FileUploader;

class EnemyController extends AbstractController
{
    private $enemyRepository;     
    private $paginator; 
    private $doctrine;
    private $validator;
    private $fileUploader;


    public function __construct(EnemyRepository $enemyRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, ValidatorInterface $validator, FileUploader $fileUploader)
    {
        $this->enemyRepository = $enemyRepository;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->fileUploader;
    }

    #[Route('/enemy/view/{gamebook}/{paragraph}', name: 'enemy_view', defaults: ['title' => 'View Enemy'])]
    public function index(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->enemyRepository->getWithSearchQueryBuilderView($q, $paragraph);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('enemy/view.html.twig', [
            'pagination' => $pagination,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);
    }

    #[Route('/enemy/create/{gamebook}/{paragraph}', name: 'enemy_create', defaults: ['title' => 'Create Enemy'])]
    public function create(Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $enemy = new Enemy();

        $form = $this->createForm(EnemyType::class, $enemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $this->fileUploader->uploadEnemy($imageFile);
                $enemy->setImage($imageFileName);
            }
            // Save
            $em = $this->doctrine->getManager();
            $em->persist($enemy);
            $em->flush();

            return $this->redirectToRoute('enemy_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }
        return $this->render('enemy/create.html.twig', [
            'form' => $form->createView(),
            'enemy' => $enemy,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }

    #[Route('/enemy/edit/{gamebook}/{paragraph}/{id}', name: 'enemy_edit', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Edit Enemy'])]
    public function edit(int $id, Request $request, string $title, string $gamebook, string $paragraph): Response
    {
        $enemy = $this->enemyRepository
            ->find($id);


        $form = $this->createForm(EnemyType::class, $enemy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $this->fileUploader->uploadEnemy($imageFile);
                $enemy->setImage($imageFileName);
            }
            // Save
            $em = $this->doctrine->getManager();
            $em->persist($enemy);
            $em->flush();

            return $this->redirectToRoute('enemy_view', ['gamebook' => $gamebook, 'paragraph' => $paragraph]);
        }

        return $this->render('enemy/edit.html.twig', [
            'form' => $form->createView(),
            'enemy' => $enemy,
            'title' => $title,
            'gamebook' => $gamebook,
            'paragraph' => $paragraph
        ]);

    }
}
