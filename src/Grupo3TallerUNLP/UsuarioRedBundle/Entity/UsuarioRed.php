<?php

namespace Grupo3TallerUNLP\UsuarioRedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsuarioRed
 */
class UsuarioRed
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $apellido;

    /**
     * @var string
     */
    private $cargo;

    /**
     * @var integer
     */
    private $dNI;

    /**
     * @var array
     */
    private $hosts;

    /**
     * @var Grupo3TallerUNLPUserBundle:User
     */
    private $usuarioSistema;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return UsuarioRed
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return UsuarioRed
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     * @return UsuarioRed
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set dNI
     *
     * @param integer $dNI
     * @return UsuarioRed
     */
    public function setDNI($dNI)
    {
        $this->dNI = $dNI;

        return $this;
    }

    /**
     * Get dNI
     *
     * @return integer
     */
    public function getDNI()
    {
        return $this->dNI;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hosts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add hosts
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $hosts
     * @return UsuarioRed
     */
    public function addHost(\Grupo3TallerUNLP\HostBundle\Entity\Host $hosts)
    {
        $this->hosts[] = $hosts;

        return $this;
    }

    /**
     * Remove hosts
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $hosts
     */
    public function removeHost(\Grupo3TallerUNLP\HostBundle\Entity\Host $hosts)
    {
        $this->hosts->removeElement($hosts);
    }

    /**
     * Get hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * Print format
     */
    public function __toString()
    {
        return $this->apellido .', '. $this->nombre;
    }
    /**
     * @var \Grupo3TallerUNLP\OficinaBundle\Entity\Oficina
     */
    private $oficina;


    /**
     * Set oficina
     *
     * @param \Grupo3TallerUNLP\OficinaBundle\Entity\Oficina $oficina
     * @return UsuarioRed
     */
    public function setOficina(\Grupo3TallerUNLP\OficinaBundle\Entity\Oficina $oficina = null)
    {
        $this->oficina = $oficina;

        return $this;
    }

    /**
     * Get oficina
     *
     * @return \Grupo3TallerUNLP\OficinaBundle\Entity\Oficina
     */
    public function getOficina()
    {
        return $this->oficina;
    }

    /**
     * Set usuarioSistema
     *
     * @param \Grupo3TallerUNLP\UserBundle\Entity\User $usuarioSistema
     * @return UsuarioRed
     */
    public function setUsuarioSistema(\Grupo3TallerUNLP\UserBundle\Entity\User $usuarioSistema = null)
    {
        $this->usuarioSistema = $usuarioSistema;

        return $this;
    }

    /**
     * Get usuarioSistema
     *
     * @return \Grupo3TallerUNLP\UserBundle\Entity\User 
     */
    public function getUsuarioSistema()
    {
        return $this->usuarioSistema;
    }
}
