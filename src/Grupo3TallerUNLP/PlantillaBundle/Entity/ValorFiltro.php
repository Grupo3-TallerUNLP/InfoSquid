<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValorFiltro
 */
class ValorFiltro
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $valor;


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
     * Set valor
     *
     * @param string $valor
     * @return ValorFiltro
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }
    /**
     * @var \Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla
     */
    private $plantilla;


    /**
     * Set plantilla
     *
     * @param \Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla $plantilla
     * @return ValorFiltro
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

    /**
     * @var \Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro
     */
    private $filtro;


    /**
     * Set filtro
     *
     * @param \Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro $filtro
     * @return ValorFiltro
     */
    public function setFiltro(\Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro $filtro = null)
    {
        $this->filtro = $filtro;

        return $this;
    }

    /**
     * Get filtro
     *
     * @return \Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro
     */
    public function getFiltro()
    {
        return $this->filtro;
    }

    /**
     * @var integer
     */
    private $filtroId;
}
