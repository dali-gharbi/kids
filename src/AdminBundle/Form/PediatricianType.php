<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PediatricianType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('Address', EntityType::class, array(
                'class' => 'AppBundle\Entity\Address',
                'choice_label' => 'longitude',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('rating', EntityType::class, array(
                'class' => 'AppBundle\Entity\Rating',
                'choice_label' => 'description',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
            ->add('speciality', EntityType::class, array(
                'class' => 'AppBundle\Entity\Speciality',
                'choice_label' => 'name',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Pediatrician'
        ));
    }
}
