<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ElevateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{

    private $passwordHasher 
    private $userRepository
    private $doctrine;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, ManagerRegistry $doctrine)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->doctrine = $doctrine;
    }

    #[Route('/registration', name: 'registration')]
    public function index(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->remove('admin');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setAdmin(0);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            
            // Set their role
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/elevateuser/{id}', name: 'elevate_user', requirements : ['id' => '\d+'], defaults: ['id' => 1, 'title' => 'Elevate User'])]
    public function elevateUser(int $id, Request $request, string $title)
    {
        $user = $this->userRepository
            ->find($id);

        $form = $this->createForm(ElevateUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['name']->getData() == "Martyn Woollard") {

            } else {
                // Save
                $em = $this->doctrine->getManager();
                $em->persist($user);
                $em->flush();
            }

            return $this->redirectToRoute('admin');
        }

        return $this->render('registration/elevate.html.twig', ['user' => $user,'form' => $form->createView(),'title' => $title]);
    }


}