<?php
	namespace Grupo3TallerUNLP\InformeBundle\Command;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Grupo3TallerUNLP\HostBundle\Entity\IPAddress;
	use Grupo3TallerUNLP\InformeBundle\Entity\Request;
	
	class AltaLogCommand extends ContainerAwareCommand{
	
		protected function configure(){
			$this
				->setName('infosquid:altalog')
				->setDescription('Dar de alta en la base de datos informacion del log de acceso de Squid')
				;
		}
		
		private function findByIP($field1, $field2, $field3, $field4)
		{			
			$em = $this->getContainer()->get('doctrine')->getManager();
			$repository= $em->getRepository('Grupo3TallerUNLPHostBundle:IPAddress');
			$ipAddress = $repository->findOneBy(array(
				'field1' => $field1,
				'field2' => $field2,
				'field3' => $field3,
				'field4' => $field4,
			));
			if (!$ipAddress) {
				$em->getConnection()->beginTransaction();
				try{
					$ipAddress = new IPAddress();
					$ipAddress->setField1($field1);
					$ipAddress->setField2($field2);
					$ipAddress->setField3($field3);
					$ipAddress->setField4($field4);
					$em->persist($ipAddress);
					$em->flush();
					$em->getConnection()->commit();
				}catch(\Exception $e){
					$em->getConnection()->rollback();
				}
			}
			return $ipAddress;
		}
		
		protected function buildRequest($matches, OutputInterface $output){
			$fields = explode('.', $matches[3][0]);
			$ip = $this->findByIP($fields[0], $fields[1], $fields[2], $fields[3]);
			$request = new Request();
			$datetime=new \DateTime();
			$datetime->setTimestamp($matches[1][0]);
			$request->setDateTime($matches[1][0]);
			$request->setHora($datetime);
			$request->setFecha($datetime);
			$request->setURL($matches[8][0]);
			if(preg_match('/DENIED/i', $matches[4][0])){
				$request->setDenegado(True);
			}else{
				$request->setDenegado(False);
			}
			$protocolo = parse_url($matches[8][0], PHP_URL_SCHEME);
			if(empty($protocolo)){
				$request->setProtocolo(Null);
			}else{
				$request->setProtocolo(parse_url($matches[8][0], PHP_URL_SCHEME));
			}
			$request->setIp($ip);
			return $request;
		}
		
		
		protected function altaLinea($line, $ultimaRequest, OutputInterface $output){
			$em = $this->getContainer()->get('doctrine')->getManager();
			$matches = array();
			preg_match_all('/^(\d+\.\d+)\s+(\d+)\s+([\d\.]+)\s+(\w+)\/([\d\.]+)\s+(\d+)\s+(\w+)\s+(\S+)/', trim($line), $matches);
			if(!$ultimaRequest ||
				($matches[1][0] > $ultimaRequest->getDateTime() 
					|| ($matches[1][0]==$ultimaRequest->getDateTime() 
						&& ($matches[3][0] != $ultimaRequest->getIp()->__toString() 
							||$matches[8][0]!= $ultimaRequest->getURL()
						)
					)
				)
			){				
				$request = $this->buildRequest($matches, $output);
				$em->persist($request);	
				return true;
			}
			else{
				return false;
			}
		}
		
		protected function altaSplFileObject(\SplFileObject $log, Request $ultimaRequest=Null, OutputInterface $output, &$inserts){
			$em = $this->getContainer()->get('doctrine')->getEntityManager();
			$batch = 0;
			while (false !== $log->current()){
				$log->seek($log->key()+10000);
			}
			if($log->key() > 0){
				$log->seek($log->key()-1);
				$log->next();
				if(!$log->current()){
					$log->seek($log->key()-1);
				}
			}
			$readfile = false !== $log->current();
			while ($readfile){
				$readfile = $this->altaLinea($log->current(), $ultimaRequest, $output);			
				if ($batch++ == 20) {
					$inserts += $batch;
					$batch = 0;
					$em->flush();
				}
				if($log->key() > 0){
					$log->seek($log->key()-1);
					$readfile = false !== $log->current();
				}
				else{
					$readfile = false;
				}
			}
			if ($batch > 0) {
				$inserts += $batch;
				$em->flush();
			}
		}
		
		protected function execute(InputInterface $input, OutputInterface $output){
			$em = $this->getContainer()->get('doctrine')->getManager();
			$archivoLog = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->getArchivoLog();
			if(file_exists($archivoLog)){
				$log = new \SplFileObject($archivoLog);
				$batch = 0;
				$inserts = 0;
				$query = $em->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r')
								  ->addOrderBy('r.dateTime', 'DESC')
								  ->addOrderBy('r.id', 'ASC')
								  ->setMaxResults(1);
				$ultimaRequest = $query->getQuery()->getOneOrNullResult();
				$this->altaSplFileObject($log, $ultimaRequest, $output, $inserts);			
				if($log->key() == 0){
					$dir1 = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->findOneById(1);
					$dir = $dir1->getDirectorioLogsAntiguos();
					$directorio = opendir($dir);
					$archivoComprimido = "";
					$fecha = "";
					$extension="";
					while ($archivo = readdir($directorio)){
						$trozos = explode(".", $archivo); 
						$extension = end($trozos);
						if ((empty($fecha) || $fecha <= filectime($dir."/".$archivo)) && ($extension == 'gz')){
							$fecha = filectime($dir."/".$archivo);
							$archivoComprimido = $archivo;
						}
					}
					$nombreArchivo = basename($archivoComprimido);
					if (isset($archivoComprimido)&& (is_null($ultimaRequest) || filectime($dir."/".$archivoComprimido) >= $ultimaRequest->getFechaAlta())){
						$batch = 0;
						if (substr($nombreArchivo, -7) == '.tar.gz') {
							$phar = new \PharData($dir."/".$archivoComprimido);
							foreach($phar as $file){
								$log = new \SplFileObject($file);
								$this->altaSplFileObject($log, $ultimaRequest, $output, $inserts);
							}
						}						
						elseif (substr($nombreArchivo, -3) == '.gz') {
							$log = gzfile($dir."/".$archivoComprimido);
							end($log);
							while ($line = current($log)) {
								$this->altaLinea($line, $ultimaRequest, $output);				
								if ($batch++ == 20) {
									$inserts += $batch;
									$batch = 0;
									$em->flush();
								}
								prev($log);
							}
							if ($batch > 0) {
								$inserts += $batch;
								$em->flush();
							}
						}
					}					
				}
				$output->writeln("Se realizaron $inserts altas.");
			}else{
				$output->writeln("No se encontro el archivo $archivoLog");
			}
		}
	}
?>