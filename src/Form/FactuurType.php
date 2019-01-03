<?php

namespace App\Form;

use App\Entity\Factuur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactuurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('timestamp', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'd-M-y',
                'attr' => array('class' => 'myDatePickerInput')
            ))
            ->add('user')
//            ->add('regels', CollectionType::class, array(
//                'entry_type' => OrderregelType::class,
//                'entry_options' => array('label' => false),
//                    'allow_add' => true,
//                    'allow_delete' => true,
//                    'delete_empty' => true,
//                    'prototype' => true,
//                    'attr' => array('class' => 'my-selector',
//                    ),
//                    'by_reference' => false)
//            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factuur::class,
        ]);
    }
}
