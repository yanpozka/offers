<?php
namespace Ganguera\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provincia
 * 
 * @ORM\Entity
 * @ORM\Table()
 */
class Provincia {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    protected $id;

    /** @ORM\Column(type="string", length=100) */
    protected $nombre;

    /** @ORM\Column(type="string", length=100) */
    protected $nombretoshow;

    public function __toString() {
        return $this->getNombre();
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getNombretoshow() {
        return $this->nombretoshow;
    }

    public function setNombretoshow($nombretoshow) {
        $this->nombretoshow = $nombretoshow;
    }
}
?>