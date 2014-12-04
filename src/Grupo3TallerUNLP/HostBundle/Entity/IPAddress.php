<?php

namespace Grupo3TallerUNLP\HostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IPAddress
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IPAddress
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="field1", type="integer")
     */
    private $field1;

    /**
     * @var integer
     *
     * @ORM\Column(name="field2", type="integer")
     */
    private $field2;

    /**
     * @var integer
     *
     * @ORM\Column(name="field3", type="integer")
     */
    private $field3;

    /**
     * @var integer
     *
     * @ORM\Column(name="field4", type="integer")
     */
    private $field4;

    /**
     * @ORM\OneToOne(targetEntity="Host", mappedBy="ipAddress")
     */
    protected $host;    
	
	/**
     * @ORM\OneToMany(targetEntity="Grupo3TallerUNLP\InformeBundle\Entity\Request", mappedBy="ip")
     */
    protected $request;

	/**
     * @ORM\OneToMany(targetEntity="Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest", mappedBy="ipH")
     */
    protected $historialrequest;

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
     * Set field1
     *
     * @param integer $field1
     * @return IPAddress
     */
    public function setField1($field1)
    {
        $this->field1 = $field1;

        return $this;
    }

    /**
     * Get field1
     *
     * @return integer
     */
    public function getField1()
    {
        return $this->field1;
    }

    /**
     * Set field2
     *
     * @param integer $field2
     * @return IPAddress
     */
    public function setField2($field2)
    {
        $this->field2 = $field2;

        return $this;
    }

    /**
     * Get field2
     *
     * @return integer
     */
    public function getField2()
    {
        return $this->field2;
    }

    /**
     * Set field3
     *
     * @param integer $field3
     * @return IPAddress
     */
    public function setField3($field3)
    {
        $this->field3 = $field3;

        return $this;
    }

    /**
     * Get field3
     *
     * @return integer
     */
    public function getField3()
    {
        return $this->field3;
    }

    /**
     * Set field4
     *
     * @param integer $field4
     * @return IPAddress
     */
    public function setField4($field4)
    {
        $this->field4 = $field4;

        return $this;
    }

    /**
     * Get field4
     *
     * @return integer
     */
    public function getField4()
    {
        return $this->field4;
    }

    /**
     * Set host
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $host
     * @return IPAddress
     */
    public function setHost(\Grupo3TallerUNLP\HostBundle\Entity\Host $host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return \Grupo3TallerUNLP\HostBundle\Entity\Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Print the entity
     */
    public function __toString()
    {
        return $this->field1 .'.'. $this->field2 .'.'. $this->field3 .'.'. $this->field4;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add request
     *
     * @param \Grupo3TallerUNLP\InformeBundle\Entity\request $request
     * @return IPAddress
     */
    public function addRequest(\Grupo3TallerUNLP\InformeBundle\Entity\request $request)
    {
        $this->request[] = $request;

        return $this;
    }

    /**
     * Remove request
     *
     * @param \Grupo3TallerUNLP\InformeBundle\Entity\request $request
     */
    public function removeRequest(\Grupo3TallerUNLP\InformeBundle\Entity\request $request)
    {
        $this->request->removeElement($request);
    }

    /**
     * Get request
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Add historialrequest
     *
     * @param \Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest $historialrequest
     * @return IPAddress
     */
    public function addHistorialrequest(\Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest $historialrequest)
    {
        $this->historialrequest[] = $historialrequest;

        return $this;
    }

    /**
     * Remove historialrequest
     *
     * @param \Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest $historialrequest
     */
    public function removeHistorialrequest(\Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest $historialrequest)
    {
        $this->historialrequest->removeElement($historialrequest);
    }

    /**
     * Get historialrequest
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistorialrequest()
    {
        return $this->historialrequest;
    }
}
