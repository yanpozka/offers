<?php

namespace Ganguera\FrontendBundle\ServicioWeb;

use Doctrine\ORM\EntityManager;

/**
 * Description of Servicio
 *
 * @author yandry
 */
class Servicio {

    private $em = null;
    protected $name;

    function __construct(EntityManager $em) {
        $this->name = 'Yan Pozo Kastillo';
        $this->em = $em;
    }

    public function ultimasOfertas($param) {
        $ofertas_list = $em->getRepository('FrontendBundle:Oferta')->findBy(
                array(), array('datecreation' => 'DESC'), 10, 0);
    }

    public function hello() {
        //return '<element value="Hola ' . $this->name . '" />';
        //return 'Hola ' . $this->name;
        return json_encode(['yan' => 'Hola ' . $this->name]);
    }

    public function echoHello($name) {
        return json_encode(['yan' => "Hola y Hello " . $name . "!"]);;
    }

}