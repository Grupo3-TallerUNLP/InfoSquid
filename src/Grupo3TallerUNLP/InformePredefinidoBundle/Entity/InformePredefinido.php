<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InformePredefinido
 */
class InformePredefinido
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
     * @var integer
     */
    private $frecuenciaTiempo;


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
     * @return InformePredefinido
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
     * Set frecuenciaTiempo
     *
     * @param integer $frecuenciaTiempo
     * @return InformePredefinido
     */
    public function setFrecuenciaTiempo($frecuenciaTiempo)
    {
        $this->frecuenciaTiempo = $frecuenciaTiempo;

        return $this;
    }

    /**
     * Get frecuenciaTiempo
     *
     * @return integer 
     */
    public function getFrecuenciaTiempo()
    {
        return $this->frecuenciaTiempo;
    }
    /**
     * @var \Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla
     */
    private $plantilla;


    /**
     * Set plantilla
     *
     * @param \Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla $plantilla
     * @return InformePredefinido
     */
    public function setPlantilla(\Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla $plantilla = null)
    {
        $this->plantilla = $plantilla;

        return $this;
    }

    /**
     * Get plantilla
     *
     * @return \Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla 
     */
    public function getPlantilla()
    {
        return $this->plantilla;
    }
	
	public function __toString()
    {
        return $this->nombre .', '. $this->frecuenciaTiempo;
    }
 
    /**
     * @var \DateTime
     */
    private $proximoEnvio;


    /**
     * Set proximoEnvio
     *
     * @param \DateTime $proximoEnvio
     * @return InformePredefinido
     */
    public function setProximoEnvio($proximoEnvio)
    {
        $this->proximoEnvio = $proximoEnvio;

        return $this;
    }

    /**
     * Get proximoEnvio
     *
     * @return \DateTime 
     */
    public function getProximoEnvio()
    {
        return $this->proximoEnvio;
    }
}
