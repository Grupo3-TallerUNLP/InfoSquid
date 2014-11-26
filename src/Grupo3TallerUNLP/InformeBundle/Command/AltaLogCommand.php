<?php
	namespace Grupo3TallerUNLP\InformeBundle\Command;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Grupo3TallerUNLP\HostBundle\Entity\IPAddress;
	
	class AltaLogCommand extends ContainerAwareCommand{
	
		protected function configure(){
			$this
				->setName('infosquid:altalog')
				->setDescription('Dar de alta en la base de datos informacion del log de acceso de Squid')
				;
		}
		
		private function findByIP($field1, $field2, $field3, $field4)
		{
			$repository = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPHostBundle:IPAddress');
			$ipAddress = $repository->findOneBy(array(
				'field1' => $field1,
				'field2' => $field2,
				'field3' => $field3,
				'field4' => $field4,
			));
			if (!$ipAddress) {
				$ipAddress = new IPAddress();
				$ipAddress->setField1($field1);
				$ipAddress->setField2($field2);
				$ipAddress->setField3($field3);
				$ipAddress->setField4($field4);
				$this->getDoctrine()->getManager()->persist($ipAddress);
			}
			return $ipAddress;
		}
		
		protected function execute(InputInterface $input, OutputInterface $output){
			$em = $this->getContainer()->get('doctrine')->getEntityManager();
			$archivoLog = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->getArchivoLog();
			$log = new \SplFileObject($archivoLog);
			$batch = 0;
			$inserts = 0;
			foreach ($log as $linea){
				preg_match_all('/^(\d+\.\d+)\s+(\d+)\s+([\d\.]+)\s+(\w+)\/([\d\.]+)\s+(\d+)\s+(\w+)\s+(\S+)/', $linea, $matches);
				$fields = explode('.', $matches[3][0]);
				$ip = $this->findByIP($fields[0], $fields[1], $fields[2], $fields[3]);
				$request = new Request();
				$request->setURL($matches[8][0]);
				$request->setDenegado(preg_match('/DENIED/i', $matches[5][0]));
				$request->setProtocolo(parse_url($matches[8][0], PHP_URL_SCHEME));
				$request->setDateTime($matches[1][0]);
				$request->setHora(date('H:i:s', $matches[1][0]));
				$request->setFecha(date('Y-m-d', $matches[1][0]));
				$request->setIp($ip);
				$em->persist($request);
				
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
			
			$output->writeln("Se realizaron $inserts altas.");
		}	
	}
?>