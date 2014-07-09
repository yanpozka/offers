<?php

namespace Ganguera\FrontendBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ganguera\FrontendBundle\Entity\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks
 * @DoctrineAssert\UniqueEntity("correo")
 * @Assert\Callback(methods={"provinciaValida"})
 */
class Usuario implements UserInterface {

    private static $saltpass = '-*Un4buena;cad3nq1e_c0nT1en3.espacio:y[cosas]_*-'; // esto no tiene mucha cosa pero bueno super-security research
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Z][a-zA-ZñÑ 0-9_\%\,\;\.\(\)\[\]]*[a-zA-Z0-9\%\.\(\)]+$/")
     */
    private $nombre;

    /**
     * @ORM\Column(name="correo", type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $correo;

    /**
     * @ORM\Column(name="ipregister", type="string", length=15)
     * @Assert\Ip()
     */
    private $ipregister;

    /**
     * @ORM\Column(name="iplastvisit", type="string", length=15)
     * @Assert\Ip()
     */
    private $iplastvisit;

    /**
     * @ORM\Column(name="dateregister", type="datetime")
     * @Assert\DateTime()
     */
    private $dateregister;

    /**
     * @ORM\Column(name="datelastvisit", type="datetime")
     * @Assert\DateTime()
     */
    private $datelastvisit;

    /**
     * @ORM\Column(name="passclave", type="string", length=100)
     */
    private $passclave;

    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(name="declaracion", type="string", length=400)
     */
    private $declaracion;

    /**
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="direccion", type="string", length=70)
     */
    private $direccion;

    /**
     * @ORM\Column(name="imgavatar", type="string", length=150, nullable=true)
     * @Assert\Image(maxSize="1M")
     */
    private $imgavatar;

    /**
     * @ORM\ManyToOne(targetEntity="Ganguera\FrontendBundle\Entity\Provincia") 
     */
    private $provincia;

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->getPassclave();
    }

    public function getRoles() {
        return array('ROLE_USUARIO');
    }

    public function getUsername() {
        return $this->getCorreo();
    }

    public function __toString() {
        //$parteTitulo = substr($this->getTitulo(), 0, strlen($parteTitulo));
        return $this->getNombre();
    }

    private function generateSalt() {
        return $this->getId() . Usuario::$saltpass . $this->getIpregister();
    }

    private $file = null;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->imgavatar) {
            // haz lo que quieras para generar un nombre único
            $this->file = $this->imgavatar;
            $this->imgavatar = 'u' . uniqid() . '.' . $this->imgavatar->guessClientExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadImg() {
        if (null === $this->file /* || !($this->imgavatar instanceof Symfony\Component\HttpFoundation\File\UploadedFile) */) {
            return;
        }
        // aquí lanzar una excepción si el archivo no se puede mover
        $this->file->move($this->getUploadRootDir(), $this->imgavatar);
        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (($file = $this->getAbsolutePath())) {
            unlink($file);
        }
    }

    public function getAbsolutePath() {
        return $this->getUploadRootDir() . '/' . $this->imgavatar;
    }

    public function getWebPath() {
        return $this->getUploadDir() . '/' . $this->imgavatar;
    }

    protected function getUploadRootDir() {
        // la ruta absoluta del directorio donde se deben guardar los archivos cargados
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // se libra del __DIR__ para no desviarse al mostrar ‘doc/image‘ en la vista.
        return 'upload/user';
    }

    public function provinciaValida(ExecutionContext $context) {
        if (empty($this->provincia) || $this->provincia == "" /* || !ctype_digit($this->provincia) */) {
            $context->addViolationAt('provincia', 'Por favor seleccione una provincia.');
        }
    }

    /**
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
        return $this;
    }

    /**
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Usuario
     */
    public function setCorreo($correo) {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo() {
        return $this->correo;
    }

    /**
     * Set ipregister
     *
     * @param string $ipregister
     * @return Usuario
     */
    public function setIpregister($ipregister) {
        $this->ipregister = $ipregister;

        return $this;
    }

    /**
     * Get ipregister
     *
     * @return string 
     */
    public function getIpregister() {
        return $this->ipregister;
    }

    /**
     * Set iplastvisit
     *
     * @param string $iplastvisit
     * @return Usuario
     */
    public function setIplastvisit($iplastvisit) {
        $this->iplastvisit = $iplastvisit;

        return $this;
    }

    /**
     * Get iplastvisit
     *
     * @return string 
     */
    public function getIplastvisit() {
        return $this->iplastvisit;
    }

    /**
     * Set dateregister
     *
     * @param \DateTime $dateregister
     * @return Usuario
     */
    public function setDateregister($dateregister) {
        //$a = new \DateTime();
        //$a->getTimestamp()
        $this->dateregister = $dateregister;

        return $this;
    }

    /**
     * Get dateregister
     *
     * @return \DateTime 
     */
    public function getDateregister() {
        return $this->dateregister;
    }

    /**
     * Set datelastvisit
     *
     * @param \DateTime $datelastvisit
     * @return Usuario
     */
    public function setDatelastvisit($datelastvisit) {
        $this->datelastvisit = $datelastvisit;

        return $this;
    }

    /**
     * Get datelastvisit
     *
     * @return \DateTime 
     */
    public function getDatelastvisit() {
        return $this->datelastvisit;
    }

    /**
     * Set passclave
     *
     * @param string $passclave
     * @return Usuario
     */
    public function setPassclave($passclave) {
        $this->passclave = $passclave; //sha1($toprep) . "";
        return $this;
    }

    /**
     * Get passclave
     *
     * @return string 
     */
    public function getPassclave() {
        return $this->passclave;
    }

    /**
     * Set idprovincia
     *
     * @param string $idprovincia
     * @return Usuario
     */
    public function setProvincia($idprovincia) {
        $this->provincia = $idprovincia;
        return $this;
    }

    /**
     * Get idprovincia
     *
     * @return string 
     */
    public function getProvincia() {
        return $this->provincia;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt) {
        $this->salt = $salt . $this->generateSalt();
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set declaracion
     *
     * @param string $declaracion
     * @return Usuario
     */
    public function setDeclaracion($declaracion) {
        $this->declaracion = $declaracion;

        return $this;
    }

    /**
     * Get declaracion
     *
     * @return string 
     */
    public function getDeclaracion() {
        return $this->declaracion;
    }

    /**
     * Set imgavatar
     *
     * @param string $imgavatar
     * @return Usuario
     */
    public function setImgavatar($imgavatar) {
        $this->imgavatar = $imgavatar;
        return $this;
    }

    /**
     * Get imgavatar
     *
     * @return string 
     */
    public function getImgavatar() {
        return $this->imgavatar;
    }

}