<?php

namespace App\Form;

use App\Entity\HeroEquipment;
use App\Entity\Hero;
use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class HeroEquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', TextType::class,['label'=>'Quantity'])
            ->add('hero', EntityType::class,['class' => Hero::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('h')->orderBy('h.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Hero'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HeroEquipment::class,
        ]);
    }
}
