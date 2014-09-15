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
     * Adds a role to the user
     * @throws Exception
     * @param Role $role
     */
    public function addRole($role)
    {
        switch ($role) {
            case 1: $this->roles = array('ROLE_USER'); break;
            case 2: $this->roles = array('ROLE_ADMIN'); break;
        }
    }
}
