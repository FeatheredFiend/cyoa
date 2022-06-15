<?php

namespace App\Form;

use App\Entity\Adventure;
use App\Entity\Gamebook;
use App\Entity\Hero;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AdventureType extends AbstractType
{
    private $token;
    private $security;

    public function __construct(TokenStorageInterface $token, Security $security)
    {
       $this->token = $token;
       $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timeelapsed', TextType::class,['label'=>'Time Elapsed'])
            ->add('gamebook', EntityType::class,['class' => Gamebook::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('g')->leftJoin('g.gamebookPermissions', 'gp')->leftJoin('gp.user', 'u')->andWhere('u.id = :user')->orWhere('g.license = :free')->setParameter('user', $user = $this->security->getUser())->setParameter('free', "FREE")->orderBy('g.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Gamebook'])
            ->add('hero', EntityType::class,['class' => Hero::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('h')->orderBy('h.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Hero'])
            ->add('user', EntityType::class,['class' => User::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.id = :user')->setParameter('user', $this->token->getToken()->getUser())->orderBy('u.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'User'])
            ->add('name', TextType::class,['label'=>'Name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adventure::class,
        ]);
    }
}
