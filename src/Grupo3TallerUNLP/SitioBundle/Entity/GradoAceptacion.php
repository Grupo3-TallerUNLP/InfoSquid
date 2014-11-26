<?php

namespace Grupo3TallerUNLP\SitioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GradoAceptacion
 */
class GradoAceptacion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $grado;


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
     * Set grado
     *
     * @param string $grado
     * @return GradoAceptacion
     */
    public function setGrado($grado)
    {
        $this->grado = $grado;

        return $this;
    }

    /**
     * Get grado
     *
     * @return string
     */
    public function getGrado()
    {
        return $this->grado;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
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
     * @return GradoAceptacion
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

    /**
     * Imprime el grado
     */
    public function __toString()
    {
        return $this->grado;
    }
}
