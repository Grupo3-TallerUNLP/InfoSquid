<?php

namespace Grupo3TallerUNLP\SitioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sitio
 */
class Sitio
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
    private $url;


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
     * @return Sitio
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
     * Set url
     *
     * @param string $url
     * @return Sitio
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @var \Grupo3TallerUNLP\GrupoBundle\Entity\Grupo
     */
    private $grupo;


    /**
     * Set grupo
     *
     * @param \Grupo3TallerUNLP\GrupoBundle\Entity\Grupo $grupo
     * @return Sitio
     */
    public function setGrupo(\Grupo3TallerUNLP\GrupoBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Grupo3TallerUNLP\GrupoBundle\Entity\Grupo 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
	
	public function __toString()
    {
        return $this->nombre;
    }
}
