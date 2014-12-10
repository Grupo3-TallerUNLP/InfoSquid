<?php

namespace Grupo3TallerUNLP\HostBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Grupo3TallerUNLP\HostBundle\Entity\Device;

class CargarDatosIniciales implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $dispositivo[] = array();
        for ($i=1 ; $i<=5; $i++) {
            $dispositivo[$i] = new Device();
        }
        $dispositivo[1]->setName('PC');
        $dispositivo[2]->setName('Notebook');
        $dispositivo[3]->setName('Teléfono');
        $dispositivo[4]->setName('Tablet');
        $dispositivo[5]->setName('Otro');
        for ($i=1 ; $i<=5; $i++) {
            $manager->persist($dispositivo[$i]);
        }

        $manager->flush();
    }
}
