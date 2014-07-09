<?php

namespace Ganguera\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProvinciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //solo nombre y slug
        $builder
            ->add('nombre')
            ->add('nombretoshow');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults( [
            'data_class' => 'Ganguera\FrontendBundle\Entity\Provincia'
        ] );
    }

    public function getName()
    {
        return 'ganguera_backendbundle_provinciatype';
    }
}