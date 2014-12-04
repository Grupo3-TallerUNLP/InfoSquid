<?php

namespace Grupo3TallerUNLP\InformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 */
class Request
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $uRL;

    /**
     * @var boolean
     */
    private $denegado;

    /**
     * @var string
     */
    private $protocolo;

    /**
     * @var string
     */
    private $dateTime;

    /**
     * @var \DateTime
     */
    private $hora;

    /**
     * @var \DateTime
     */
    private $fecha;

	private $fechaAlta;

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
     * Set uRL
     *
     * @param string $uRL
     * @return Request
     */
    public function setURL($uRL)
    {
        $this->uRL = $uRL;

        return $this;
    }

    /**
     * Get uRL
     *
     * @return string 
     */
    public function getURL()
    {
        return $this->uRL;
    }

    /**
     * Set denegado
     *
     * @param boolean $denegado
     * @return Request
     */
    public function setDenegado($denegado)
    {
        $this->denegado = $denegado;

        return $this;
    }

    /**
     * Get denegado
     *
     * @return boolean 
     */
    public function getDenegado()
    {
        return $this->denegado;
    }

    /**
     * Set protocolo
     *
     * @param string $protocolo
     * @return Request
     */
    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;

        return $this;
    }

    /**
     * Get protocolo
     *
     * @return string 
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    /**
     * Set dateTime
     *
     * @param string $dateTime
     * @return Request
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return string 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     * @return Request
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime 
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Request
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    /**
     * @var \Grupo3TallerUNLP\HostBundle\Entity\IPAddress
     */
    private $ip;


    /**
     * Set ip
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\IPAddress $ip
     * @return Request
     */
    public function setIp(\Grupo3TallerUNLP\HostBundle\Entity\IPAddress $ip = null)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return \Grupo3TallerUNLP\HostBundle\Entity\IPAddress 
     */
    public function getIp()
    {
        return $this->ip;
    }
	
	 public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
	
	public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }
	
	public function __construct(){
		$this->fechaAlta = date('Y-m-d H:i:s', time());
	}
}
