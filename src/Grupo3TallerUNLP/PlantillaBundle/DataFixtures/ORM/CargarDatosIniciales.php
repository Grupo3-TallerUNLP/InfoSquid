<?php

namespace Grupo3TallerUNLP\PlantillaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro;

class CargarDatosIniciales implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $filtro = array();
        for ($i=1; $i<=11 ; $i++) {
            $filtro[$i] = new Filtro();
        }
        $filtro[1]->setNombre('Rango de días');
        $filtro[2]->setNombre('Hora desde');
        $filtro[3]->setNombre('Hora hasta');
        $filtro[4]->setNombre('Oficina');
        $filtro[5]->setNombre('Usuario');
        $filtro[6]->setNombre('IP');
        $filtro[7]->setNombre('IP desde');
        $filtro[8]->setNombre('IP hasta');
        $filtro[9]->setNombre('Grupo');
        $filtro[10]->setNombre('Sitios');
        $filtro[11]->setNombre('Sólo tráfico denegado');
        for ($i=1; $i<=11 ; $i++) {
            $manager->persist($filtro[$i]);
        }

        $manager->flush();
    }
}
