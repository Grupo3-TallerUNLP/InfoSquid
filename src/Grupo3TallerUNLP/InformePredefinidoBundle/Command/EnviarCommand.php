<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $this->verbose('Listo');

        if (!$informesPredefinidos) {
            $this->write('NO se encontraron informes predefinidos para enviar hoy.');
            return;
        }

        $this->write('Se encontraron '. count($informesPredefinidos) .' informes predefinidos para enviar hoy.');

        $this->debug('Obteniendo el controlador InformeController... ', false);
        $informeController = $this->getContainer()->get('infosquid.informe.informe_controller');
        $this->debug('Listo');

        $enviados = 0;
        $enviadosErrores = 0;
        foreach ($informesPredefinidos as $informePredefinido) {
            $plantilla = $informePredefinido->getPlantilla();
            $usuarioSistema = $plantilla->getUsuariosistema();
            $usuarioRed = $usuarioSistema->getUsuarioRed();
            $this->verbose('Se enviará el informe de la plantilla "'. $plantilla->getNombre() .'" del usuario '. $usuarioRed->__toString() .' <'. $usuarioSistema->getEmail() .'>');

            $this->debug('Obteniendo los requests de la plantilla... ', false);

            $datos = $informeController->datosPlantilla($plantilla->getId(), $this->getEntityManager(), $usuarioSistema);
            $this->debug('Listo');

            $this->debug('Generando el PDF... ', false);
            $html = $this->getContainer()->get('templating')
                ->render(
                    'Grupo3TallerUNLPInformeBundle:Informe:mostrarInforme.pdf.twig',
                    $datos
                );
            $pdf = $this->getContainer()->get('knp_snappy.pdf')->getOutputFromHtml($html);
            $this->debug('Listo');

            if ($this->enviarInforme($usuarioSistema->getEmail(), $pdf)) {
                $enviados++;
                $this->verbose('Se envió el mensaje');
            } else {
                $enviadosErrores++;
                $this->verbose('NO se pudo enviar el mensaje');
            }

            $this->debug('Programando próximo envio del informe... ', false);
            $this->programarSiguienteEnvioInforme($informePredefinido);
            $this->debug('Listo');
        }

        $this->write('Se enviaron '. $enviados .' emails');
        if ($enviadosErrores) {
            $this->write('No se pudieron enviar '. $enviadosErrores .' emails');
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

    private function enviarInforme($direccionEmail, $informe)
    {
        $this->debug('Creando mensaje... ', false);

        $attachment = \Swift_Attachment::newInstance($informe, 'Informe.pdf', 'application/pdf');

        $message = \Swift_Message::newInstance()
            ->setSubject('InfoSquid - Informe')
            ->setFrom('info@infosquid.com')
            ->setTo($direccionEmail)
            ->setBody('Se adjunta el informe generado el '. strftime('%e-%m-%Y a las %H:%M hs'))
            ->attach($attachment)
            ;

        $this
            ->debug('Listo')
            ->verbose('Enviando mensaje... ', false)
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

        $this->debug('El informe se enviará en '. $dias .' días a partir del '. $informePredefinido->getProximoEnvio()->format('d-m-Y'), false);

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
