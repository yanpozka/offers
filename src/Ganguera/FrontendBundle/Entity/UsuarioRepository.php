<?php
namespace Ganguera\FrontendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * @author yandry
 */
class UsuarioRepository extends EntityRepository {

    function insertar($ip, &$usuario, $encf) {
        $t = new \DateTime('today');

        $usuario->setDatelastvisit($t);
        $usuario->setDateregister($t);
        $usuario->setIpregister($ip);
        $usuario->setIplastvisit($ip);
        if (!$usuario->getDeclaracion()) {
            $usuario->setDeclaracion("ninguna");
        }
        if (!$usuario->getDireccion()) {
            $usuario->setDireccion("");
        }
        $encoder = $encf->getEncoder($usuario);
        $usuario->setSalt(md5(time()));
        $passwordCodificado = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
        $usuario->setPassclave($passwordCodificado);
    }
}
?>