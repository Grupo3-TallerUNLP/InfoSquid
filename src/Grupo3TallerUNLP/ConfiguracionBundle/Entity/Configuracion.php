<?php

namespace Grupo3TallerUNLP\ConfiguracionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuracion
 */
class Configuracion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $paginacion;

    /**
     * @var string
     */
    private $directorioLogsAntiguos;

    /**
     * @var string
     */
    private $archivoDeLog;

    /**
     * @var integer
     */
    private $tiempoMantenimiento;


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
     * Set paginacion
     *
     * @param integer $paginacion
     * @return Configuracion
     */
    public function setPaginacion($paginacion)
    {
        $this->paginacion = $paginacion;

        return $this;
    }

    /**
     * Get paginacion
     *
     * @return integer 
     */
    public function getPaginacion()
    {
        return $this->paginacion;
    }

    /**
     * Set directorioLogsAntiguos
     *
     * @param string $directorioLogsAntiguos
     * @return Configuracion
     */
    public function setDirectorioLogsAntiguos($directorioLogsAntiguos)
    {
        $this->directorioLogsAntiguos = $directorioLogsAntiguos;

        return $this;
    }

    /**
     * Get directorioLogsAntiguos
     *
     * @return string 
     */
    public function getDirectorioLogsAntiguos()
    {
        return $this->directorioLogsAntiguos;
    }

    /**
     * Set archivoDeLog
     *
     * @param string $archivoDeLog
     * @return Configuracion
     */
    public function setArchivoDeLog($archivoDeLog)
    {
        $this->archivoDeLog = $archivoDeLog;

        return $this;
    }

    /**
     * Get archivoDeLog
     *
     * @return string 
     */
    public function getArchivoDeLog()
    {
        return $this->archivoDeLog;
    }

    /**
     * Set tiempoMantenimiento
     *
     * @param integer $tiempoMantenimiento
     * @return Configuracion
     */
    public function setTiempoMantenimiento($tiempoMantenimiento)
    {
        $this->tiempoMantenimiento = $tiempoMantenimiento;

        return $this;
    }

    /**
     * Get tiempoMantenimiento
     *
     * @return integer 
     */
    public function getTiempoMantenimiento()
    {
        return $this->tiempoMantenimiento;
    }
}
