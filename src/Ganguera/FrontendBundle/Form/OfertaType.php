<?php

namespace Ganguera\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * @author yandry
 */
class OfertaType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('titulo')
            ->add('datestart', 'date')
            ->add('datefinish', 'date')
            ->add('cost', 'integer')
            ->add('description', 'textarea')
            ->add('provincia')
            ->add('path_img', 'file', array('required' => false))
            ->add('path_img_1', 'file', array('required' => false))
            ->add('path_img_2', 'file', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ganguera\FrontendBundle\Entity\Oferta'
        ));
    }
    
    public function getName() {
        return "ganguera_frontendbundle_ofertatype";
    }
}