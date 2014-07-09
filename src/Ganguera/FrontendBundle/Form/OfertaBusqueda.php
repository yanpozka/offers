<?php

namespace Ganguera\FrontendBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

//use Symfony\Component\Validator\ExecutionContext;

/**
 * OfertaBusqueda
 */
//@Assert\Callback(methods={"costoMenorCostoMax"})
class OfertaBusqueda {
    /* function __construct() {
      $this->titulo = "";
      $this->description = "";
      $this->costo_min = 0;
      $this->costo_max = 0;
      $this->fecha = "";
      } */

    /**
     * @Assert\Regex(pattern="/(^[A-Z][a-zA-ZñÑ 0-9_\%\,\;\.\(\)\[\]]*[a-zA-Z0-9\%\.\(\)]*$)|/")
     */
    public $titulo;
    //@Assert\Length(min=0)
    public $description;

    /**
     * @Assert\Regex(pattern="/^[0-9]*$/")
     * @Assert\Range(min=0, max=1000000)
     */
    //@Assert\Min(limit=0)@Assert\MinLength(limit=0)
    public $costo_min;

    /**
     * @Assert\Regex(pattern="/^[0-9]*$/")
     * @Assert\Range(min=0, max=1000000)
     */
    //@Assert\Min(limit=0)@Assert\MinLength(limit=0)
    public $costo_max;

    /**
     * 
     */
    public $fecha;

    // @Assert\Date()

    /**
     * @Assert\True(message = "El costo minimo debe ser menor o igual que el costo maximo.")
     */
    public function isCostoMenorCostoMax(/* ExecutionContext $context */) {
        if (empty($this->costo_min) || empty($this->costo_max))
            return true;
        return ($this->costo_min <= $this->costo_max);
    }

    public function __toString() {
        return "titulo=" . $this->titulo
                . " costo_min=" . $this->costo_min
                . " costo_max=" . $this->costo_max
                . " fecha=" . $this->fecha;
    }

}