<?php

namespace Grupo3TallerUNLP\HostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Host
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Host
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
     * @ORM\OneToOne(targetEntity="IPAddress", inversedBy="host")
     * @ORM\JoinColumn(name="ip_address", referencedColumnName="id")
     */
    private $ipAddress;

    private $ipAddressFields;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Device", inversedBy="hosts")
     * @ORM\JoinColumn(name="device", referencedColumnName="id")
     */
    private $device;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed", inversedBy="hosts", cascade={"persist"})
     * @ORM\JoinTable(name="UsuarioRed_Host")
     * joinColumns={@ORM\JoinColumn(name="host_id", referencedColumnName="id")}
     * inverseJoinColumns={@ORM\JoinColumn(name="usuariored_id", referencedColumnName="id")}
     */
    private $networkUsers;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Grupo3TallerUNLP\OficinaBundle\Entity\Oficina", inversedBy="hosts")
     * @ORM\JoinColumn(name="office", referencedColumnName="id")
     */
    private $office;


    public function __construct()
    {
        $this->ipAddressFields = new \SplFixedArray(4);
        $this->networkUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Host
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return integer
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Get ipAddressField1
     *
     * @return integer
     */
    public function getIpAddressField1()
    {

        return $this->ipAddressFields[0];
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Host
     */
    public function setIpAddressField1($ipAddressField1)
    {
        $this->ipAddressFields[0] = $ipAddressField1;

        return $this;
    }

    /**
     * Get ipAddressField2
     *
     * @return integer
     */
    public function getIpAddressField2()
    {
        return $this->ipAddressFields[1];
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Host
     */
    public function setIpAddressField2($ipAddressField2)
    {
        $this->ipAddressFields[1] = $ipAddressField2;

        return $this;
    }

    /**
     * Get ipAddressField3
     *
     * @return integer
     */
    public function getIpAddressField3()
    {
        return $this->ipAddressFields[2];
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Host
     */
    public function setIpAddressField3($ipAddressField3)
    {
        $this->ipAddressFields[2] = $ipAddressField3;

        return $this;
    }

    /**
     * Get ipAddressField4
     *
     * @return integer
     */
    public function getIpAddressField4()
    {
        return $this->ipAddressFields[3];
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return Host
     */
    public function setIpAddressField4($ipAddressField4)
    {
        $this->ipAddressFields[3] = $ipAddressField4;

        return $this;
    }

    /**
     * Set device
     *
     * @param integer $device
     * @return Host
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return integer
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set networkUsers
     *
     * @param array $networkUsers
     * @return Host
     */
    public function setNetworkUsers($networkUsers)
    {
        $this->networkUsers = $networkUsers;

        return $this;
    }

    /**
     * Get networkUsers
     *
     * @return array
     */
    public function getNetworkUsers()
    {
        return $this->networkUsers;
    }

    /**
     * Set office
     *
     * @param integer $office
     * @return Host
     */
    public function setOffice($office)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return integer
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Add networkUsers
     *
     * @param \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $networkUsers
     * @return Host
     */
    public function addNetworkUser(\Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $networkUsers)
    {
        $this->networkUsers[] = $networkUsers;

        return $this;
    }

    /**
     * Remove networkUsers
     *
     * @param \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $networkUsers
     */
    public function removeNetworkUser(\Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $networkUsers)
    {
        $this->networkUsers->removeElement($networkUsers);
    }

	public function __toString(){
		$ofi = $this -> office;

		if(empty ($ofi)){
			return $this->device -> __toString() . ' ' . $this->ipAddress ->__toString();
		}
		else{
			return $this->device -> __toString() . ' ' . $this->ipAddress ->__toString() . '  (' . $this->office-> getNombre() . ')';
		}

    }
}
