<?php

namespace AdminBundle\Form;

use Petkopara\MultiSearchBundle\Form\Type\MultiSearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SharedExperienceFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('search', MultiSearchType::class, array(
            'class' => 'AppBundle:SharedExperience',
            'search_fields' => array( //optional, if it's empty it will search in the all entity columns
                'id',
                'title',
                'description',
                'likes',
                'theme',
            ),
        ));

        $builder->setMethod('GET');


    }


    public function getBlockPrefix()
    {
        return null;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
