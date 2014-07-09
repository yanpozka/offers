<?php

namespace Ganguera\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author yandry
 */
class BuscarOfertaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titulo')
                ->add('description')
                ->add('costo_min', 'integer')
                ->add('costo_max', 'integer')
                ->add('fecha')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ganguera\FrontendBundle\Form\OfertaBusqueda'
        ));
    }

    public function getName() {
        return "ganguera_frontendbundle_buscarofertatype";
    }
}