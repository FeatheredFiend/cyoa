<?php

namespace App\Form;

use App\Entity\ParagraphSpell;
use App\Entity\Paragraph;
use App\Entity\Spell;
use App\Entity\ParagraphSpellCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphSpellType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paragraph', EntityType::class,['class' => Spell::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('p')->orderBy('p.id', 'ASC');}, 'choice_label' => 'number', 'label' => 'Paragraph'])
            ->add('spell', EntityType::class,['class' => Spell::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('s')->orderBy('s.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Spell'])
            ->add('paragraphspellcategory', EntityType::class,['class' => Spell::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('psc')->orderBy('psc.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Paragraph Spell Category'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphSpell::class,
        ]);
    }
}
