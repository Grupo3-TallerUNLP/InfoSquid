<?php

namespace Grupo3TallerUNLP\ConfiguracionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Grupo3TallerUNLP\ConfiguracionBundle\Entity\Configuracion;

class CargarDatosIniciales implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $configuracion = new Configuracion();
        $configuracion->setPaginacion(15);
        $configuracion->setDirectorioLogsAntiguos('');
        $configuracion->setArchivoDeLog('');
        $configuracion->setTiempoMantenimiento(30);

        $manager->persist($configuracion);
        $manager->flush();
    }

}
