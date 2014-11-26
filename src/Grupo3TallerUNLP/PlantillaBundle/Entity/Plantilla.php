<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantilla
 */
class Plantilla
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
     * @return Plantilla
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
     * @return Plantilla
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
     * @var \Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido
     */
    private $informepredefinido;


    /**
     * Set informepredefinido
     *
     * @param \Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido $informepredefinido
     * @return Plantilla
     */
    public function setInformepredefinido(\Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido $informepredefinido = null)
    {
        $this->informepredefinido = $informepredefinido;

        return $this;
    }

    /**
     * Get informepredefinido
     *
     * @return \Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido 
     */
    public function getInformepredefinido()
    {
        return $this->informepredefinido;
    }
	
	public function __toString()
    {
        return $this->nombre ;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $valorfiltro;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valorfiltro = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add valorfiltro
     *
     * @param \Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro $valorfiltro
     * @return Plantilla
     */
    public function addValorfiltro(\Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro $valorfiltro)
    {
        $this->valorfiltro[] = $valorfiltro;

        return $this;
    }

    /**
     * Remove valorfiltro
     *
     * @param \Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro $valorfiltro
     */
    public function removeValorfiltro(\Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro $valorfiltro)
    {
        $this->valorfiltro->removeElement($valorfiltro);
    }

    /**
     * Get valorfiltro
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValorfiltro()
    {
        return $this->valorfiltro;
    }
    /**
     * @var \Grupo3TallerUNLP\UserBundle\Entity\User
     */
    private $usuariosistema;


    /**
     * Set usuariosistema
     *
     * @param \Grupo3TallerUNLP\UserBundle\Entity\User $usuariosistema
     * @return Plantilla
     */
    public function setUsuariosistema(\Grupo3TallerUNLP\UserBundle\Entity\User $usuariosistema = null)
    {
        $this->usuariosistema = $usuariosistema;

        return $this;
    }

    /**
     * Get usuariosistema
     *
     * @return \Grupo3TallerUNLP\UserBundle\Entity\User 
     */
    public function getUsuariosistema()
    {
        return $this->usuariosistema;
    }
}
