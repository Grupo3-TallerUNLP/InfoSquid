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
}
