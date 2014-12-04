<?php

namespace Grupo3TallerUNLP\InformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialRequest
 */
class HistorialRequest extends Request
{
	public static function initialize(Request $request){
		$HistorialRequest = new self;
		$HistorialRequest->setDateTime($request->getDateTime());
		$HistorialRequest->setHora($request->getHora());
		$HistorialRequest->setFecha($request->getFecha());
		$HistorialRequest->setURL($request->getURL());
		$HistorialRequest->setDenegado($request->getDenegado());
		$HistorialRequest->setProtocolo($request->getProtocolo());
		$HistorialRequest->setIp($request->getIP());
		$HistorialRequest->setFechaAlta($request->getFechaAlta());
		return $HistorialRequest;
	}

    /**
     * @var \Grupo3TallerUNLP\HostBundle\Entity\IPAddress
     */
    private $ipH;


    /**
     * Set ipH
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\IPAddress $ipH
     * @return HistorialRequest
     */
    public function setIpH(\Grupo3TallerUNLP\HostBundle\Entity\IPAddress $ipH = null)
    {
        $this->ipH = $ipH;

        return $this;
    }

    /**
     * Get ipH
     *
     * @return \Grupo3TallerUNLP\HostBundle\Entity\IPAddress 
     */
    public function getIpH()
    {
        return $this->ipH;
    }
}
