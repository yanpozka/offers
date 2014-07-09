<?php

namespace Ganguera\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ganguera\FrontendBundle\Entity\OfertaRepository")
 * @ORM\HasLifecycleCallbacks
 * @Assert\Callback(methods={"fechaInicioMeorFin", "provinciaValida"})
 * 
 */
class Oferta {
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="titulo", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Z][a-zA-ZñÑ\ 0-9_\%\,\;\.\(\)\[\]]*[a-zA-Z0-9\%\.\(\)]+$/")
     */
    private $titulo;

    /**
     * @ORM\Column(name="datecreation", type="datetime")
     */
    private $datecreation;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="datestart", type="date")
     * @Assert\Date()
     */
    private $datestart;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="datefinish", type="date")
     * @Assert\Date()
     */
    private $datefinish;

    /**
     * @ORM\Column(name="cost", type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(min=0, max=1000000)
     */
    private $cost;

    /**
     * @ORM\Column(name="description", type="string", length=1024)
     */
    private $description;
    
    /**
     * @ORM\Column(name="path_img", type="string", length=256, nullable=true)
     * @Assert\Image(maxSize="512k")
     */
    public $path_img;
    
    /**
     * @ORM\Column(name="path_img_1", type="string", length=256, nullable=true)
     * @Assert\Image(maxSize="512k")
     */
    public $path_img_1;
    
    /**
     * @ORM\Column(name="path_img_2", type="string", length=256, nullable=true)
     * @Assert\Image(maxSize="512k")
     */
    public $path_img_2;

    /** @ORM\ManyToOne(targetEntity="Ganguera\FrontendBundle\Entity\Usuario") */
    private $usuario;

    /** 
     * @ORM\ManyToOne(targetEntity="Ganguera\FrontendBundle\Entity\Provincia")
     */
    private $provincia;

    public function __toString() {
        //$parteTitulo = substr($this->getTitulo(), 0, strlen($parteTitulo));
        return $this->getTitulo() . " - costo: $" . $this->getCost();
    }
    
    public function fechaInicioMeorFin(ExecutionContext $context) {
        if ( $this->getDatestart() >= $this->getDatefinish() ) {
            $context->addViolationAt('datestart', 'La fecha de inicio no debe ser mayor o igual que la fecha de finalizacion.');
            $context->addViolationAt('datefinish', 'La fecha de fin no debe ser menor o igual que la fecha de inicio.');
        }
    }
    
    public function provinciaValida(ExecutionContext $context) {
        if (empty($this->provincia) || $this->provincia == "" /*|| !ctype_digit($this->provincia)*/) {
            $context->addViolationAt('provincia', 'Por favor seleccione una provincia.');
        }
    }
    
    /**
     * Get id
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function setProvincia($idprovincia) {
        $this->provincia = $idprovincia;
        return $this;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Oferta
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     * @return Oferta
     */
    public function setDatecreation($datecreation) {
        $this->datecreation = $datecreation;
        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime 
     */
    public function getDatecreation() {
        return $this->datecreation;
    }

    /**
     * Set datestart
     *
     * @param \DateTime $datestart
     * @return Oferta
     */
    public function setDatestart($datestart) {
        $this->datestart = $datestart;
        return $this;
    }

    /**
     * Get datestart
     *
     * @return \DateTime 
     */
    public function getDatestart() {
        return $this->datestart;
    }

    /**
     * Set datefinish
     *
     * @param \DateTime $datefinish
     * @return Oferta
     */
    public function setDatefinish($datefinish) {
        $this->datefinish = $datefinish;
        return $this;
    }

    /**
     * Get datefinish
     *
     * @return \DateTime 
     */
    public function getDatefinish() {
        return $this->datefinish;
    }

    /**
     * Set idusuario
     *
     * @param string $idusuario
     * @return Oferta
     */
    public function setUsuario($idusuario) {
        $this->usuario = $idusuario;
        return $this;
    }

    /**
     * Get idusuario
     *
     * @return string 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return Oferta
     */
    public function setCost($cost) {
        $this->cost = $cost;
        return $this;
    }

    /**
     * Get cost
     *
     * @return float 
     */
    public function getCost() {
        return $this->cost;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Oferta
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }
    
    private $file = null, $file_1 = null, $file_2 = null;
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->path_img) {
            // haz lo que quieras para generar un nombre único
            $this->file = $this->path_img;
            $this->path_img = 'of' . uniqid() . '.' . $this->path_img->guessClientExtension();
        }
        if (null !== $this->path_img_1) {
            // haz lo que quieras para generar un nombre único
            $this->file_1 = $this->path_img_1;
            $this->path_img_1 = 'of' . uniqid() . '.' . $this->path_img_1->guessClientExtension();
        }
        if (null !== $this->path_img_2) {
            // haz lo que quieras para generar un nombre único
            $this->file_2 = $this->path_img_2;
            $this->path_img_2 = 'of' . uniqid() . '.' . $this->path_img_2->guessClientExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadImg() {
        if (null !== $this->file) {
            $this->file->move($this->getUploadRootDir(), $this->path_img);
            unset($this->file);
        }
        if (null !== $this->file_1) {
            $this->file_1->move($this->getUploadRootDir(), $this->path_img_1);
            unset($this->file_1);
        }
        if (null !== $this->file_2) {
            $this->file_2->move($this->getUploadRootDir(), $this->path_img_2);
            unset($this->file_2);
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (($file = $this->getAbsolutePathFirst())) {
            unlink($file);
        }
        if (($file = $this->getAbsolutePathSec())) {
            unlink($file);
        }
        if (($file = $this->getAbsolutePathTr())) {
            unlink($file);
        }
    }

    public function getAbsolutePathFirst() {
        return $this->getUploadRootDir() . '/' . $this->path_img;
    }

    public function getWebPathFirst() {
        return $this->getUploadDir() . '/' . $this->path_img;
    }
    
    public function getAbsolutePathSec() {
        return $this->getUploadRootDir() . '/' . $this->path_img_1;
    }

    public function getWebPathSec() {
        return $this->getUploadDir() . '/' . $this->path_img_1;
    }
    
    public function getAbsolutePathTr() {
        return $this->getUploadRootDir() . '/' . $this->path_img_2;
    }

    public function getWebPathTr() {
        return $this->getUploadDir() . '/' . $this->path_img_2;
    }

    protected function getUploadRootDir() {
        // la ruta absoluta del directorio donde se deben guardar los archivos cargados
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // se libra del __DIR__ para no desviarse al mostrar ‘doc/image‘ en la vista.
        return 'upload/oferta';
    }
}