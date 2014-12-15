<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;
use Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed;
use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;
use Grupo3TallerUNLP\HostBundle\Entity\IpAddress;

class InformeController extends Controller
{
    public function indexAction()
    {
        return $this->render('Grupo3TallerUNLPInformeBundle:Informe:index.html.twig');
    }

	private function listUsuarios()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->createQueryBuilder('u')
							  ->innerJoin('u.hosts', 'h')
							  ->join('u.oficina', 'o')
							  ->addOrderBy('o.nombre', 'ASC')
							  ->addOrderBy('u.nombre', 'ASC');

		$table = $table->getQuery()->getResult();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function listOficinas()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->createQueryBuilder('o')
							  ->innerJoin('o.hosts', 'h')
							  ->addOrderBy('o.nombre', 'ASC');
		$table = $table->getQuery()->getResult();
		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	public function generarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$plantillas = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->findByUsuariosistema($user);
		$o = array();
		$oficinas =  $this->listOficinas();
		$usuarios = $this->listUsuarios();
		$grupos = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();
		$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findAll();
		if(! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			$em = $this->getDoctrine()->getManager();
			$conect = $this->get('security.context')->getToken()->getUser()->getId();
			$usuarios= $em->getRepository('Grupo3TallerUNLPUserBundle:User')->createQueryBuilder('u')
										->innerJoin('u.usuarioRed', 'r')
										->innerJoin('r.oficina', 'o')
										->where('u.id = :id')->setParameter('id', $conect)
										->getQuery()->getResult();
		}

		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:generarInforme.html.twig',array(
			'plantillas' => $plantillas,
			'oficinas'	=> $oficinas,
			'usuarios'	=> $usuarios,
			'grupos'	=> $grupos,
			'sitios'	=> $sitios,
		));
	}

	public function exportarAction(Request $request, $type){
		$ids = $request->request->get('request');
		$requests = explode('-', $ids);
		$datos = array();
		$em = $this->getDoctrine()->getManager();
		foreach($requests as $request){
			$dato=$em->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r')
											->innerJoin('r.ip', 'ip')
											->leftJoin('ip.host', 'h')
											->leftJoin('h.networkUsers', 'u')
											->leftJoin('h.office', 'f')
											->where ('r.id = :id')
											->setParameter('id', $request)
											->getQuery()->getOneOrNullResult();
			if (!is_null($dato)) {
				$datos[] = $dato;
			}
		}
		if($type == 'csv'){
			$csv_sep = ";";
			$csv_file = "informe.csv";
			$csv="";
			foreach ($datos as $dato){
				$csv.=$dato->getFecha()->format('d-m-Y').$csv_sep;
				$csv.=$dato->getHora()->format('H:i').$csv_sep;
				if($dato->getDenegado()){
					$csv.='SI'.$csv_sep;
				}else{
					$csv.='NO'.$csv_sep;
				}
				$csv.=$dato->getIP()->__toString() .$csv_sep;
				if($dato->getIP()->getHost()){
					$csv.=$dato->getIP()->getHost()->getDevice().$csv_sep;
					if($dato->getIP()->getHost()->getNetworkUsers()){
						$usuarios=$dato->getIP()->getHost()->getNetworkUsers();
						foreach ($usuarios as $usuario){
							$csv.=$usuario->__toString().'-';
						}
						$csv = substr($csv, 0, -1);
						$csv.=$csv_sep;
					}else{
						$csv.= " ".$csv_sep;
					}
					if($dato->getIP()->getHost()->getOffice()){
						$csv.=$dato->getIP()->getHost()->getOffice()->getNombre().$csv_sep;
					}else{
						$csv.= " ".$csv_sep;
					}
				}
				else{
					$csv.= " ".$csv_sep;
					$csv.= " ".$csv_sep;
					$csv.= " ".$csv_sep;
				}
				$csv.= $dato->getUrl();
				$csv.= PHP_EOL;
			}

			return new Response($csv, 200, array(
				'Content-Type' => 'application/vnd.ms-excel',
				'Content-Disposition' => 'attachment; filename=Informe_'. date('d-m-Y_H-i-s') .'.csv',
			));
		}elseif($type == 'pdf'){
			$html = $this->renderView('Grupo3TallerUNLPInformeBundle:Informe:mostrarInforme.pdf.twig', array(
				'resultados' => $datos,
			));

			return new Response(
				$this->get('knp_snappy.pdf')->getOutputFromHtml($html),
				200,
				array(
					'Content-Type' => 'application/pdf',
					'Content-Disposition' => 'attachment; filename=Informe_'. date('d-m-Y_H-i-s') .'.pdf',
				)
			);
		}
	}

	public function datosPlantilla($id, $em = null, $usuarioSistema = null)
	{
		if (is_null($em)) {
			$em = $this->getDoctrine()->getManager();
		}
		if (is_null($usuarioSistema)) {
			$usuarioSistema = $this->get('security.context')->getToken()->getUser();
		}

		$plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);
		$valorfiltro = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->findByPlantilla($plantilla);
		$filtros = array();
        
		if(! $usuarioSistema->hasRole('ROLE_ADMIN')) {
			$usuario = $usuarioSistema->getUsuarioRed();
			$oficina = $usuario->getOficina();
			$filtros[4] = $oficina->getId();
		}
		foreach($valorfiltro as $valor){
			$filtros[$valor->getFiltro()->getId()] = $valor->getValor();
		}
		
		$where = 'where';
		$query = $em->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
		if(array_key_exists(1, $filtros)){
			$fecha = date('Y-m-d',time() - 86400*$filtros[1]); //ver
			$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $fecha);
			$where = 'andWhere';
			$fecha = date('d-m-Y',time() - 86400*$filtros[1]);
			$informe[] ='Fecha Desde: ' . $fecha;
		}
		if (array_key_exists(2, $filtros) && array_key_exists(3, $filtros)) {
			$query->$where('r.hora >= :hora_desde')->setParameter('hora_desde', $filtros[2]);
			$where = 'andWhere';
			$query->$where('r.hora <= :hora_hasta')->setParameter('hora_hasta', $filtros[3]);
			$informe[] ='Hora Desde: ' . $filtros[2];
			$informe[] ='Hora Hasta: ' . $filtros[3];
		}elseif(array_key_exists(2, $filtros)){
			$query->$where('r.hora >= :hora_desde')->setParameter('hora_desde', $filtros[2]);
			$where = 'andWhere';
			$informe[] ='Hora Desde: ' . $filtros[2];
		}elseif(array_key_exists(3, $filtros)){
			$query->$where('r.hora <= :hora_hasta')->setParameter('hora_hasta', $filtros[3]);
			$where = 'andWhere';
			$informe[] ='Hora Hasta: ' . $filtros[3];
		}
		if(array_key_exists(6, $filtros)){
			$dirIp = explode('.', $filtros[6]);
			$ip = $em->getRepository('Grupo3TallerUNLPHostBundle:IPAddress')->findOneBy(array('field1'=>$dirIp[0], 'field2'=>$dirIp[1], 'field3'=>$dirIp[2], 'field4'=>$dirIp[3]));
			$query->innerJoin('r.ip', 'i');
			$query->$where('i = :ip')->setParameter('ip', $ip);
			$informe[] ='IP: ' . $filtros[6];
			$where = 'andWhere';
		}elseif(array_key_exists(7, $filtros) || array_key_exists(8, $filtros)){
			if(array_key_exists(7, $filtros)){
				$informe[] ='IP Desde: ' . $filtros[7];
				$query->innerJoin('r.ip', 'i');
				$query->$where($query->expr()->gte(
						$query->expr()->concat('i.field1',
							$query->expr()->concat($query->expr()->literal('.'),
								$query->expr()->concat('i.field2',
									$query->expr()->concat($query->expr()->literal('.'),
										$query->expr()->concat('i.field3',
											$query->expr()->concat($query->expr()->literal('.'), 'i.field4')
										)
									)
								)
							)
						)
					, ':ip_desde'));
				$query->setParameter('ip_desde', $filtros[7]);
				$where = 'andWhere';
			}
			if(array_key_exists(8, $filtros)){
				$informe[] ='IP Hasta: ' . $filtros[8];
				if(!array_key_exists(7, $filtros)){
					$query->innerJoin('r.ip', 'i');
				}
				$query->$where($query->expr()->lte(
						$query->expr()->concat('i.field1',
							$query->expr()->concat($query->expr()->literal('.'),
								$query->expr()->concat('i.field2',
									$query->expr()->concat($query->expr()->literal('.'),
										$query->expr()->concat('i.field3',
											$query->expr()->concat($query->expr()->literal('.'), 'i.field4')
										)
									)
								)
							)
						)
					, ':ip_hasta'));
				$query->setParameter('ip_hasta', $filtros[8]);
			}
		}elseif(array_key_exists(4, $filtros)){
			$of = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($filtros[4]);
			$informe[] ='Oficina: ' . $of->getNombre() ;
			$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
			$query->$where('h.office= :oficina')->setParameter('oficina', $filtros[4]);
			$where='andWhere';
		}
		elseif(array_key_exists(5, $filtros)){
			$us = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($filtros[5]);
			$informe[] ='Usuario: ' . $us->__toString();
			$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h')->innerJoin('h.networkUsers', 'u');
			$query->$where('u.id= :usuario')->setParameter('usuario', $filtros[5]);
			$where='andWhere';
		}
		if(array_key_exists(9, $filtros)){
			$gr = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($filtros[9]);

			$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findByGrupo($filtros[9]);
			if(count($sitios)>0){
				$informe[] ='Grupo: ' . $gr->getNombre();
				$like = array();
				foreach ($sitios as $sitio) {
					$like[] = $query->expr()->like('r.uRL', $query->expr()->literal('%'.$sitio->getUrl().'%'));
				}
				$query->$where(call_user_func_array(array($query->expr(), 'orX'), $like));
				$where = 'andWhere';
			}
		}elseif(array_key_exists(10, $filtros)){
			$sitio = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($filtros[10]);
			if($sitio){
				$query->$where('r.uRL LIKE :sitio')->setParameter('sitio', '%'.$sitio->getUrl().'%');
				$where = 'andWhere';
			}
			$informe[] ='Sitio: ' . $sitio->getNombre();
		}
		if(array_key_exists(11, $filtros)){
			$query->$where('r.denegado = True');
			$informe[] ='Trafico Denegado: ' . 'Si';
		}

		$resultados = $query->orderBy('r.dateTime', 'DESC')->getQuery()->getResult();

		$requests = array();
		foreach ($resultados as $request) {
			$requests[] = $request->getId();
		}
		$requests = implode('-', $requests);

		return array(
			'resultados' => $resultados,
			'requests'   => $requests,
			'filtros'    => $informe,
			'plantilla'  => $plantilla->getNombre(),
		);
	}

	public function mostrarPlantillaAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);
		if($plantilla){
			$usuarioSistema = $this->get('security.context')->getToken()->getUser();
			if($plantilla->getUsuariosistema() == $usuarioSistema){
				return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarInforme.html.twig', $this->datosPlantilla($id));
			}else{
				$this->get('session')->getFlashBag()->add('error', 'La plantilla buscada no existe');
				return $this->redirect($this->generateUrl('informe_generar'));
			}
		}else{
			$this->get('session')->getFlashBag()->add('error', 'La plantilla buscada no existe');
			return $this->redirect($this->generateUrl('informe_generar'));
		}
	}

	public function mostrarFiltroAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$filtro1 = $request->request->get('filtro1');
        $filtro2 = $request->request->get('filtro2');
		$filtros = $request->request->get('filtros');
		$validos = array();
		$error = $this->validarFiltros($filtros, $filtro1, $filtro2, $validos);
		if (!is_null($error)) {
			$this->get('session')->getFlashBag()->add('error', $error);
		}
		else {
			if(! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
				$em = $this->getDoctrine()->getManager();
				$usuario = $this->get('security.context')->getToken()->getUser()->getUsuarioRed();
				$oficina = $usuario->getOficina();
				$filtros['oficina'] = $oficina->getId();
				$validos['oficina']='oficina';
		}
			$where = 'where';
			$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
			$informe = array();
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$query->$where('r.fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
			}elseif (in_array('r.fecha_hasta', $validos)) {
				$query->$where('fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$where = 'andWhere';
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}
			if (in_array('hora_desde', $validos) && in_array('hora_hasta', $validos)) {
				$query->$where('r.hora >= :hora_desde')->setParameter('hora_desde', $filtros['hora_desde']);
				$where = 'andWhere';
				$query->$where('r.hora <= :hora_hasta')->setParameter('hora_hasta', $filtros['hora_hasta']);
				$informe[] ='Hora Desde: ' . $filtros['hora_desde'];
				$informe[] ='Hora Hasta: ' . $filtros['hora_hasta'];
			}elseif(in_array('hora_desde', $validos)){
				$query->$where('r.hora >= :hora_desde')->setParameter('hora_desde', $filtros['hora_desde']);
				$where = 'andWhere';
				$informe[] ='Hora Desde: ' . $filtros['hora_desde'];
			}elseif(in_array('hora_hasta', $validos)){
				$query->$where('r.hora <= :hora_hasta')->setParameter('hora_hasta', $filtros['hora_hasta']);
				$where = 'andWhere';
				$informe[] ='Hora Desde: ' . $filtros['hora_desde'];
			}
			if(in_array('ip', $validos)){
				$ip = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPHostBundle:IPAddress')->findOneBy(array ('field1' => $filtros['ip'][0], 'field2' => $filtros['ip'][1], 'field3' => $filtros['ip'][2], 'field4' => $filtros['ip'][3]));
				$query->innerJoin('r.ip', 'i');
				$query->$where('i = :ip')->setParameter('ip', $ip);
				$where = 'andWhere';
				$informe[] ='IP: ' . implode('.', $filtros['ip']);
			}elseif(in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)){
				if(in_array('ip_desde', $validos)){
					$informe[] ='IP Desde: ' . implode('.', $filtros['ip_desde']);
					$query->innerJoin('r.ip', 'i');
					$query->$where($query->expr()->gte(
							$query->expr()->concat('i.field1',
								$query->expr()->concat($query->expr()->literal('.'),
									$query->expr()->concat('i.field2',
										$query->expr()->concat($query->expr()->literal('.'),
											$query->expr()->concat('i.field3',
												$query->expr()->concat($query->expr()->literal('.'), 'i.field4')
											)
										)
									)
								)
							)
						, ':ip_desde'));
					$query->setParameter('ip_desde', implode('.', $filtros['ip_desde']));
					$where = 'andWhere';
				}
				if(in_array('ip_hasta', $validos)){
					$informe[] ='IP Hasta: ' . implode('.', $filtros['ip_hasta']);
					if(!in_array('ip_desde', $validos)){
						$query->innerJoin('r.ip', 'i');
					}
					$query->$where($query->expr()->lte(
							$query->expr()->concat('i.field1',
								$query->expr()->concat($query->expr()->literal('.'),
									$query->expr()->concat('i.field2',
										$query->expr()->concat($query->expr()->literal('.'),
											$query->expr()->concat('i.field3',
												$query->expr()->concat($query->expr()->literal('.'), 'i.field4')
											)
										)
									)
								)
							)
						, ':ip_hasta'));
					$query->setParameter('ip_hasta', implode('.', $filtros['ip_hasta']));
				}
			}elseif(in_array('oficina', $validos)){
				$of = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($filtros['oficina']);
				$informe[] ='Oficina: ' . $of->__toString() ;
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
				$query->$where('h.office= :oficina')->setParameter('oficina', $filtros['oficina']);
				$where='andWhere';
			}elseif(in_array('usuario', $validos)){
				$us = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($filtros['usuario']);
				$informe[] ='Usuario: ' . $us->__toString();
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h')->innerJoin('h.networkUsers', 'u');
				$query->$where('u.id= :usuario')->setParameter('usuario', $filtros['usuario']);
				$where='andWhere';
			}
			if(in_array('grupo', $validos)){
				$gr = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($filtros['grupo']);
				$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findByGrupo($filtros['grupo']);
				if(count($sitios)>0){
					$informe[] ='Grupo: ' . $gr->getNombre();
					$like = array();
					foreach ($sitios as $sitio) {
						$like[] = $query->expr()->like('r.uRL', $query->expr()->literal('%'.$sitio->getUrl().'%'));
					}
					$query->$where(call_user_func_array(array($query->expr(), 'orX'), $like));
					$where = 'andWhere';
				}
			}elseif(in_array('sitio', $validos)){
				$sitio = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($filtros['sitio']);
				if($sitio){
					$query->$where('r.uRL LIKE :sitio')->setParameter('sitio', '%'.$sitio->getUrl().'%');
					$where = 'andWhere';
				}
				$informe[] ='Sitio: ' . $sitio->getNombre();
			}
			if(in_array('traficodenegado', $validos)){
				$query->$where('r.denegado = True');
				$where = 'andWhere';
				$informe[] ='Trafico Denegado: ' . 'Si';
			}

			$resultados = $query->orderBy('r.dateTime', 'DESC')->getQuery()->getResult();

			$requests = array();
			foreach ($resultados as $request) {
				$requests[] = $request->getId();
			}
			$requests = implode('-', $requests);

			return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarInforme.html.twig',array(
				'resultados' => $resultados,
				'requests'   => $requests,
				'filtros'    => $informe,
			));
		}
		return $this->redirect($this->generateUrl('informe_generar'));
	}

	private function validarFiltros($filtros, $filtro1, $filtro2, &$validos)
	{
		$ok = false;
		$validos = array();
		foreach ($filtros as $id => $f){
            if (in_array($id, array('fecha_desde','fecha_hasta', 'hora_desde', 'hora_hasta', 'traficodenegado')) || $id == $filtro1 || $id == $filtro2 || ($filtro1 == 'ip_desde' && $id == 'ip_hasta')) {
                if (is_array($f)) {
                    if ((!empty($f[0]) || $f[0]=='0') && (!empty($f[1]) || $f[1]=='0') && (!empty($f[2]) || $f[2]=='0') && (!empty($f[3]) || $f[3]=='0')) {
                        $ok = true;
                        $validos[$id] = $id;
                    }
                } else {
                    If (!empty ($f)){
                        $ok=true;
                        $validos[$id] = $id;
                    }
                }
            }
		}
		if ($ok) {
			if (in_array('fecha_desde', $validos) && preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $filtros['fecha_desde'])) {
				$fecha = explode('-', $filtros['fecha_desde']);
				$filtros['fecha_desde'] = "$fecha[2]-$fecha[1]-$fecha[0]";
			}
			if (in_array('fecha_hasta', $validos) && preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $filtros['fecha_hasta'])) {
				$fecha = explode('-', $filtros['fecha_hasta']);
				$filtros['fecha_hasta'] = "$fecha[2]-$fecha[1]-$fecha[0]";
			}
			if (in_array('fecha_desde', $validos) && !preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $filtros['fecha_desde'])) {
				return 'La fecha desde debe tener un formato dd-mm-aaaa';
			} elseif (in_array('fecha_hasta', $validos) && !preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $filtros['fecha_hasta'])) {
				return 'La fecha hasta debe tener un formato dd-mm-aaaa';
			} elseif (in_array('hora_desde', $validos) && !preg_match('/^\d{2}\:\d{2}$/', $filtros['hora_desde'])) {
				return 'La hora desde debe tener el formato hh:mm';
			} elseif (in_array('hora_hasta', $validos) && !preg_match('/^\d{2}\:\d{2}$/', $filtros['hora_hasta'])) {
				return 'La hora hasta debe tener el formato hh:mm';
			} elseif (
				in_array('ip', $validos)
				&& (is_int($filtros['ip'][0]) && $filtros['ip'][0] >= 0 && $filtros['ip'][0] <= 255)
				&& (is_int($filtros['ip'][1]) && $filtros['ip'][1] >= 0 && $filtros['ip'][1] <= 255)
				&& (is_int($filtros['ip'][2]) && $filtros['ip'][2] >= 0 && $filtros['ip'][2] <= 255)
				&& (is_int($filtros['ip'][3]) && $filtros['ip'][3] >= 0 && $filtros['ip'][3] <= 255)
			) {
				return 'La IP debe estar compuesta de cuatro campos de 0 a 255 cada uno';
			} elseif (in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)) {
				if (in_array('ip_desde', $validos)
					&& (is_int($filtros['ip_desde'][0]) && $filtros['ip_desde'][0] >= 0 && $filtros['ip_desde'][0] <= 255)
					&& (is_int($filtros['ip_desde'][1]) && $filtros['ip_desde'][1] >= 0 && $filtros['ip_desde'][1] <= 255)
					&& (is_int($filtros['ip_desde'][2]) && $filtros['ip_desde'][2] >= 0 && $filtros['ip_desde'][2] <= 255)
					&& (is_int($filtros['ip_desde'][3]) && $filtros['ip_desde'][3] >= 0 && $filtros['ip_desde'][3] <= 255)
				) {
					return 'La IP desde debe estar compuesta de cuatro campos de 0 a 255 cada uno';
				}
				if (in_array('ip_hasta', $validos)
					&& (is_int($filtros['ip_hasta'][0]) && $filtros['ip_hasta'][0] >= 0 && $filtros['ip_hasta'][0] <= 255)
					&& (is_int($filtros['ip_hasta'][1]) && $filtros['ip_hasta'][1] >= 0 && $filtros['ip_hasta'][1] <= 255)
					&& (is_int($filtros['ip_hasta'][2]) && $filtros['ip_hasta'][2] >= 0 && $filtros['ip_hasta'][2] <= 255)
					&& (is_int($filtros['ip_hasta'][3]) && $filtros['ip_hasta'][3] >= 0 && $filtros['ip_hasta'][3] <= 255)
				) {
					return 'La IP hasta debe estar compuesta de cuatro campos de 0 a 255 cada uno';
				}
				if (in_array('ip_desde', $validos) && in_array('ip_hasta', $validos)) {
					if (implode('.', $filtros['ip_desde']) >= implode('.', $filtros['ip_hasta'])) {
						return 'La IP hasta debe ser mayor que la IP desde';
					}
				}
			} else {
				return null;
			}
		}
	}
}
