<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;

class AdminController extends AbstractController
{

    private $userRepository; 
    private $paginator;


    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;

    }

    #[Route('/admin', name: 'admin', defaults: ['title' => 'Admin'])]
    public function index(string $title, Request $request): Response
    {
        $q = $request->query->get('q');
        $queryBuilder = $this->userRepository->getWithSearchQueryBuilderView($q);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );


        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
            'title' => $title
        ]);
    }
}
