<?php

namespace App\Form;

use App\Entity\MagicEffect;
use App\Entity\Magic;
use App\Entity\MagicEffectAttribute;
use App\Entity\MagicEffectOperator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MagicEffectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('magiceffectvalue', TextType::class,['label'=>'Magic Effect Value'])
            ->add('magic', EntityType::class,['class' => Magic::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('m')->orderBy('m.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Magic'])
            ->add('magiceffectoperator', EntityType::class,['class' => MagicEffectOperator::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('meo')->orderBy('meo.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Magic Effect Operator'])
            ->add('magiceffectattribute', EntityType::class,['class' => MagicEffectAttribute::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('mea')->orderBy('mea.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Magic Effect Attribute'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MagicEffect::class,
        ]);
    }
}
