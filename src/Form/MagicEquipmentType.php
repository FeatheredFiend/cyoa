<?php

namespace App\Form;

use App\Entity\MagicEquipment;
use App\Entity\Magic;
use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MagicEquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('magic', EntityType::class,['class' => Magic::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('m')->orderBy('m.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Magic'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MagicEquipment::class,
        ]);
    }
}
