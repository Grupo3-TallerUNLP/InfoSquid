<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido;

class EnviarCommand extends ContainerAwareCommand
{

    private $output;
    private $debug;
    private $verbose;

    protected function configure()
    {
        $this
            ->setName('infosquid:informepredefinido:enviar')
            ->setDescription('Generar y enviar por email informes predefinidos')
            ->addOption('debug', null, InputOption::VALUE_NONE, 'Modo debug')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output  = $output;
        $this->debug   = $input->getOption('debug');
        $this->verbose = $input->getOption('verbose');

        $this->verbose('Buscando los informes predefinidos a enviar... ', false);
        $informesPredefinidos = $this->getInformesPredefinidos();
        $this->verbose('Listo.', false);

        if (!$informesPredefinidos) {
            $this->write('NO se encontraron informes predefinidos para enviar hoy.');
            return;
        }

        $this->write('Se encontraron '. count($informesPredefinidos) .' informes predefinidos para enviar hoy.');

        foreach ($informesPredefinidos as $informePredefinido) {
            $plantilla = $informePredefinido->getPlantilla();
            $usuarioSistema = $plantilla->getUsuariosistema();
            $usuarioRed = $usuarioSistema->getUsuarioRed();
            $this->verbose('Se enviará el informe de la plantilla "'. $plantilla->getNombre() .'" del usuario '. $usuarioRed->__toString() .' <'. $usuarioSistema->getEmail() .'>');

            if ($this->enviarInforme($usuarioSistema->getEmail(), 'path_to_file')) {
                $this->write('Se envió el mensaje');
            } else {
                $this->write('NO se pudo enviar el mensaje');
            }

            $this->debug('Programando próximo envio del informe... ', false);
            $this->programarSiguienteEnvioInforme($informePredefinido);
            $this->debug('Listo');
        }
    }



    private function getInformesPredefinidos()
    {
        $this
            ->debug('Listo')
            ->debug('Buscando los informes de hoy.. ', false)
            ;

        $hoy = new \DateTime('today');
        $informespredefinidos = $this->getRepository()->createQueryBuilder('ip')
            ->where('ip.proximoEnvio <= :hoy')
            ->orderBy('ip.proximoEnvio')
            ->setParameter('hoy', $hoy)
            ->getQuery()->getResult()
            ;

        if ($informespredefinidos) {
            return $informespredefinidos;
        } else {
            return false;
        }
    }

    private function enviarInforme($direccionEmail, $pathInforme)
    {
        $this->debug('Creando mensaje... ', false);

        $message = \Swift_Message::newInstance()
            ->setSubject('prueba')
            ->setFrom('info@infosquid.com')
            ->setTo($direccionEmail)
            ->setBody('hola que tal')
            // ->attach(\Swift_Attachment::fromPath($pathInforme))
            ;

        $this
            ->debug('Listo')
            ->write('Enviando mensaje... ', false)
            ;

        return $this->getContainer()->get('mailer')->send($message);
    }

    private function programarSiguienteEnvioInforme(InformePredefinido $informePredefinido)
    {
        $this
            ->debug('')
            ->debug('Calculando diferencia en días... ', false)
            ;
        $dias = $informePredefinido->getFrecuenciaTiempo();

        $this->debug('El informe se enviará en '. $dias .' días', false);

        $intervalo = new \DateInterval('P'. $dias .'D');
        $envio = $informePredefinido->getProximoEnvio();
        $envio->add($intervalo);

        $informePredefinido->setProximoEnvio($envio);

        $this->debug(', el día '. $informePredefinido->getProximoEnvio()->format('d-m-Y'));

        $this->debug('Actualizando el informe predefinido... ', false);

        $em = $this->getEntityManager();
        $em->persist($informePredefinido);
        $em->flush();
    }



    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido');
    }



    private function write($message = '', $ln = true)
    {
        if ($ln) {
            $this->output->writeln($message);
        } else {
            $this->output->write($message);
        }
        return $this;
    }

    private function verbose($message = '', $ln = true)
    {
        if ($this->verbose || $this->debug) {
            if ($ln) {
                $this->output->writeln($message);
            } else {
                $this->output->write($message);
            }
        }
        return $this;
    }

    private function debug($message = '', $ln = true)
    {
        if ($this->debug) {
            if ($ln) {
                $this->output->writeln($message);
            } else {
                $this->output->write($message);
            }
        }
        return $this;
    }

}
