<?php

namespace App\Form;

use App\Entity\EquipmentEffect;
use App\Entity\Equipment;
use App\Entity\EquipmentEffectOperator;
use App\Entity\EquipmentEffectAttribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EquipmentEffectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipmenteffectvalue', TextType::class,['label'=>'Equipment Effect Value'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
            ->add('equipmenteffectoperator', EntityType::class,['class' => EquipmentEffectOperator::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('eeo')->orderBy('eeo.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment Effect Operator'])
            ->add('equipmenteffectattribute', EntityType::class,['class' => EquipmentEffectAttribute::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('eea')->orderBy('eea.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment Effect Attribute'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EquipmentEffect::class,
        ]);
    }
}
