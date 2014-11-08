<?php

namespace Grupo3TallerUNLP\HostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Device
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Host", mappedBy="device")
     */
    protected $hosts;


    public function __construct()
    {
        $this->hosts = new Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Device
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add hosts
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $hosts
     * @return Device
     */
    public function addHost(\Grupo3TallerUNLP\HostBundle\Entity\Host $hosts)
    {
        $this->hosts[] = $hosts;

        return $this;
    }

    /**
     * Remove hosts
     *
     * @param \Grupo3TallerUNLP\HostBundle\Entity\Host $hosts
     */
    public function removeHost(\Grupo3TallerUNLP\HostBundle\Entity\Host $hosts)
    {
        $this->hosts->removeElement($hosts);
    }

    /**
     * Get hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHosts()
    {
        return $this->hosts;
    }


    public function __toString()
    {
        return $this->getName();
    }
}
