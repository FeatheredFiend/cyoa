<?php

namespace App\Form;

use App\Entity\ParagraphActionSpell;
use App\Entity\ParagraphAction;
use App\Entity\Spell;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphActionSpellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paragraphaction', EntityType::class,['class' => ParagraphAction::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pa')->orderBy('pa.id', 'ASC');}, 'choice_label' => 'text', 'label' => 'Paragraph Action'])
            ->add('spell', EntityType::class,['class' => Spell::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('s')->orderBy('s.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Spell'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphActionSpell::class,
        ]);
    }
}
