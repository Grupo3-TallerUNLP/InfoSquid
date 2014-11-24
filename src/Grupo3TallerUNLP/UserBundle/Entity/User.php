<?php

namespace Grupo3TallerUNLP\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed", inversedBy="usuarioSistema")
     * @ORM\JoinColumn(name="usuariored", referencedColumnName="id")
     */
    private $usuarioRed;

    /**
     * Adds a role to the user
     * @throws Exception
     * @param Role $role
     */
    public function addRole($role)
    {
        $this->roles = array($role);
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
     * Set usuarioRed
     *
     * @param \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuarioRed
     * @return User
     */
    public function setUsuarioRed(\Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed $usuarioRed = null)
    {
        $this->usuarioRed = $usuarioRed;

        return $this;
    }

    /**
     * Get usuarioRed
     *
     * @return \Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed
     */
    public function getUsuarioRed()
    {
        return $this->usuarioRed;
    }

    /**
     *
     */
    public function setAdministrador($administrador)
    {
        if ($administrador) {
            $this->setRoles(array('ROLE_ADMIN'));
        } else {
            $this->setRoles(array('ROLE_USER'));
        }

        return $this;
    }

    /**
     *
     */
    public function getAdministrador()
    {
        return in_array('ROLE_ADMIN', $this->getRoles());
    }

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setEnabled(true);
    }

    /**
     *
     */
    public function __toString()
    {
        return $this->getUsuarioRed() .' ('. $this->username .')';
    }
}
