<?php
	namespace Grupo3TallerUNLP\InformeBundle\Command;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Grupo3TallerUNLP\InformeBundle\Entity\Request;
	use Grupo3TallerUNLP\InformeBundle\Entity\HistorialRequest;
	
	class MantenimientoCommand extends ContainerAwareCommand{
	
		protected function configure(){
			$this
				->setName('infosquid:mantenimiento')
				->setDescription('Pasar de la tabla request de la base de datos la tuplas con la fecha con mayor antigüedad a la seteada en la configuracion a una tabla historial');
		}
				
		protected function execute(InputInterface $input, OutputInterface $output){
			$em = $this->getContainer()->get('doctrine')->getManager();
			$tiempoMantenimiento = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->findOneById(1)->getTiempoMantenimiento();
			$fecha = date('Y-m-d', time() - $tiempoMantenimiento*86400);
			$output->writeln($fecha);
			$query = $em->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r')
							  ->where('r.fecha < :fecha')->setParameter('fecha', $fecha);
			$inserts = 0;
			$batch = 0;
			$requests = $query->getQuery()->getResult();
			foreach($requests as $request){
				$historialRequest = HistorialRequest::initialize($request);
				$em->persist($historialRequest);
				$em->remove($request);
				if ($batch++ == 20) {
					$inserts += $batch;
					$batch = 0;
					$em->flush();
				}				
			}
			if ($batch > 0) {
				$inserts += $batch;
				$em->flush();
			}				
			$output->writeln("Se realizaron $inserts altas en el historial.");
		}
	}
?>