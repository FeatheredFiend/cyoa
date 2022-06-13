<?php

namespace App\Form;

use App\Entity\ParagraphEquipment;
use App\Entity\Paragraph;
use App\Entity\Equipment;
use App\Entity\ParagraphEquipmentCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphEquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paragraph', EntityType::class,['class' => Paragraph::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('p')->orderBy('p.id', 'ASC');}, 'choice_label' => 'text', 'label' => 'Paragraph'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
            ->add('quantity', TextType::class,['label'=>'Quantity'])
            ->add('paragraphequipmentcategory', EntityType::class,['class' => ParagraphEquipmentCategory::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pec')->orderBy('pec.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Category'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphEquipment::class,
        ]);
    }
}
