<?php

namespace App\Form;

use App\Entity\ParagraphAction;
use App\Entity\Paragraph;
use App\Entity\ParagraphActionCategory;
use App\Entity\ParagraphActionOperator;
use App\Entity\ParagraphActionTarget;
use App\Entity\HeroAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamebook', TextType::class,['label'=>'Gamebook', 'required' => false, 'mapped' => false])
            ->add('text', TextType::class,['label'=>'Text'])
            ->add('actionvalue', TextType::class,['label'=>'Action Value'])
            ->add('paragraph', EntityType::class,['class' => Paragraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('p')->orderBy('p.id', 'ASC');}, 'choice_label' => 'number', 'label' => 'Paragraph'])
            ->add('paragraphactioncategory', EntityType::class,['class' => ParagraphActionCategory::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pac')->orderBy('pac.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Paragraph Action Category'])
            ->add('paragraphactionoperator', EntityType::class,['class' => ParagraphActionOperator::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pao')->orderBy('pao.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Paragraph Action Operator'])
            ->add('paragraphactionattribute', EntityType::class,['class' => HeroAttribute::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('ha')->orderBy('ha.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Paragraph Action Attribute'])
            ->add('paragraphactiontarget', EntityType::class,['class' => ParagraphActionTarget::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pat')->orderBy('pat.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Paragraph Action Target'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphAction::class,
        ]);
    }
}
