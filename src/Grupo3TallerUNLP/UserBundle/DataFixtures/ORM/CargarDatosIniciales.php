<?php

namespace Grupo3TallerUNLP\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed;
use Grupo3TallerUNLP\UserBundle\Entity\User as UsuarioSistema;

class CargarDatosIniciales implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // oficina
        $oficina = new Oficina();
        $oficina->setNombre('Informática');
        $manager->persist($oficina);

        // usuario de red
        $adminRed = new UsuarioRed();
        $adminRed->setNombre('Usuario');
        $adminRed->setApellido('Administrador');
        $adminRed->setOficina($oficina);
        $manager->persist($adminRed);

        // usuario de sistema
        $adminSis = new UsuarioSistema();
        $adminSis->setUsername('admin');
        $adminSis->setEmail('admin@example.com');
        $adminSis->setPlainPassword('admin');
        $adminSis->setAdministrador(true);
        $adminSis->setUsuarioRed($adminRed);
        $manager->persist($adminSis);

        $manager->flush();
    }
}
