<?php

namespace App\Form;

use App\Entity\Spell;
use App\Entity\Magic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class SpellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['label'=>'Name'])
            ->add('magic', EntityType::class,['class' => Magic::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('m')->orderBy('m.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Magic'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Spell::class,
        ]);
    }
}
