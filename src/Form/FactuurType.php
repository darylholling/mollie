<?php

namespace App\Form;

use App\Entity\Factuur;
use Symfony\Component\Form\AbstractType;
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
            ->add('user');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factuur::class,
        ]);
    }
}
