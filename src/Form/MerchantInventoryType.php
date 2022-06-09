<?php

namespace App\Form;

use App\Entity\MerchantInventory;
use App\Entity\Merchant;
use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MerchantInventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cost', TextType::class,['label'=>'Cost'])
            ->add('quantity', TextType::class,['label'=>'Quantity'])
            ->add('merchant', EntityType::class,['class' => Merchant::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('m')->orderBy('m.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Merchant'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MerchantInventory::class,
        ]);
    }
}
