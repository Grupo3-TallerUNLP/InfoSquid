<?php

namespace Grupo3TallerUNLP\SitioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Grupo3TallerUNLP\SitioBundle\Entity\GradoAceptacion;

class CargarDatosIniciales implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $gradoAceptacion[] = array();
        for ($i=1 ; $i<=5; $i++) {
            $gradoAceptacion[$i] = new GradoAceptacion();
        }
        $gradoAceptacion[1]->setGrado('Muy Malo');
        $gradoAceptacion[2]->setGrado('Malo');
        $gradoAceptacion[3]->setGrado('Regular');
        $gradoAceptacion[4]->setGrado('Bueno');
        $gradoAceptacion[5]->setGrado('Muy Bueno');
        for ($i=1 ; $i<=5; $i++) {
            $manager->persist($gradoAceptacion[$i]);
        }

        $manager->flush();
    }
}
