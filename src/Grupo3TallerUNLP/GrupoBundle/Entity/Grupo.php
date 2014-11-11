<?php

namespace Grupo3TallerUNLP\GrupoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 */
class Grupo
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
     * @return Grupo
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
     * @return Grupo
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
     *  @ORM\ManyToOne(targetEntity="Grupo3TallerUNLP\SItioBundle\Entity\Sitio", inversedBy="grupo", cascade={"persist", "remove"} )
     */
    private $sitios;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sitios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sitios
     *
     * @param \Grupo3TallerUNLP\SitioBundle\Entity\Sitio $sitios
     * @return Grupo
	 * 
     */
    public function addSitio(\Grupo3TallerUNLP\SitioBundle\Entity\Sitio $sitios)
    {
        $this->sitios[] = $sitios;

        return $this;
    }

    /**
     * Remove sitios
     *
     * @param \Grupo3TallerUNLP\SitioBundle\Entity\Sitio $sitios
     */
    public function removeSitio(\Grupo3TallerUNLP\SitioBundle\Entity\Sitio $sitios)
    {
        $this->sitios->removeElement($sitios);
    }

    /**
     * Get sitios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSitios()
    {
        return $this->sitios;
    }
	
	public function __toString()
    {
        return $this->nombre;
    }
}