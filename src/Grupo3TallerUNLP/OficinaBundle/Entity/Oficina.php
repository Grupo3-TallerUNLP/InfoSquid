<?php

namespace Grupo3TallerUNLP\OficinaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oficina
 */
class Oficina
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
    private $descripcion;

    /**
     * @var string
     */
    private $ubicacion;

    /**
     * @var string
     */
    private $director;


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
     * @return Oficina
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Oficina
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set ubicacion
     *
     * @param string $ubicacion
     * @return Oficina
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return string 
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * Set director
     *
     * @param string $director
     * @return Oficina
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return string 
     */
    public function getDirector()
    {
        return $this->director;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $usuariosdered;
	/**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $hosts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuariosdered = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hosts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add usuariosdered
     *
     * @param \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuariosdered
     * @return Oficina
     */
    public function addUsuariosdered(\Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuariosdered)
    {
        $this->usuariosdered[] = $usuariosdered;

        return $this;
    }

    /**
     * Remove usuariosdered
     *
     * @param \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuariosdered
     */
    public function removeUsuariosdered(\Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuariosdered)
    {
        $this->usuariosdered->removeElement($usuariosdered);
    }

    /**
     * Get usuariosdered
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuariosdered()
    {
        return $this->usuariosdered;
    }
	
	/**
     * Add host
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $host
     * @return Oficina
     */
    public function addHosts(\Grupo3TallerUNLP\HostBundle\Entity\Host $host)
    {
        $this->hosts[] = $host;

        return $this;
    }

    /**
     * Remove host
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $host
     */
    public function removeHosts(\Grupo3TallerUNLP\HostBundle\Entity\Host $host)
    {
        $this->hosts->removeElement($host);
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
	
	public function __toString()
    {
        return $this->nombre .' (' . $this->ubicacion  .')';
    }
}