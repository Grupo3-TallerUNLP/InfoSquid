<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filtro
 */
class Filtro
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
     * @return Filtro
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
     * @var \Doctrine\Common\Collections\Collection
     */
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
     * @return Filtro
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
     * @var string
     */
    private $tipo;


    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Filtro
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
