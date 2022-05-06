<?php

namespace App\Form;

use App\Entity\Paragraph;
use App\Entity\Gamebook;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ParagraphType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class,['label'=>'Number'])
            ->add('text', TextareaType::class,['label'=>'Text'])
            ->add('gamebook', EntityType::class,['class' => Gamebook::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('g')->orderBy('g.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Gamebook'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paragraph::class,
        ]);
    }
}
