<?php

namespace App\Form;

use App\Entity\ParagraphDirectionEquipmentRequired;
use App\Entity\ParagraphDirection;
use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ParagraphDirectionEquipmentRequiredType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paragraphdirection', EntityType::class,['class' => ParagraphDirection::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('pd')->orderBy('pd.id', 'ASC');}, 'choice_label' => 'text', 'label' => 'Paragraph Direction'])
            ->add('equipment', EntityType::class,['class' => Equipment::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('e')->orderBy('e.id', 'ASC');}, 'choice_label' => 'name', 'label' => 'Equipment'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParagraphDirectionEquipmentRequired::class,
        ]);
    }
}
