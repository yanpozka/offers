<?php

namespace Ganguera\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombre', 'text', array('label' => 'Nombre'))
                ->add('correo', 'email')
                ->add('passclave', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Las dos contraseñas deben coincidir.',
                    'label' => 'Clave',
                    'options' => array('label' => ' ')
                ))
                ->add('declaracion', 'textarea', array('required' => false, 'label' => "Declaracion"))
                ->add('phone')
                ->add('direccion', 'textarea', array('required' => false, 'label' => "Direccion"))
                ->add('imgavatar', 'file', array('required' => false, 'label'=>'Imagen de avatar'))
                ->add('provincia')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ganguera\FrontendBundle\Entity\Usuario'
        ));
    }

    public function getName() {
        return 'ganguera_backendbundle_usuariotype';
    }

}