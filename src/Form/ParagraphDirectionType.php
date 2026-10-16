<?php

namespace App\Form;

use App\Entity\ParagraphDirection;
use App\Entity\Paragraph;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphDirectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamebook', TextType::class,['label'=>'Gamebook', 'required' => false, 'mapped' => false])
            ->add('text', TextareaType::class,['label'=>'Text'])
            ->add('maxaccess', TextType::class,['label'=>'Max Access'])
            ->add('redirectparagraph', TextType::class,['label'=>'Redirect Paragraph'])
            ->add('paragraph', EntityType::class,['class' => Paragraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('p')->orderBy('p.id', 'ASC');}, 'choice_label' => 'number', 'label' => 'Paragraph'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphDirection::class,
        ]);
    }
}
