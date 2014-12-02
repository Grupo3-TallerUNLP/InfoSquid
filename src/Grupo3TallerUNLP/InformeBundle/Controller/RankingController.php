<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;
use Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed;
use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;

class RankingController extends Controller
{
	
	public function sitiosGenerarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$grupos = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:sitiosGenerar.html.twig',array(
			'oficinas'	=> $oficinas,
			'grupos'	=> $grupos,
		));
	}
	
	public function sitiosMostrarAction(Request $request)
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
			$where = 'where';
			$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
			$informe = [];
			$informe[] = 'Cantidad :' . $filtros['cantidad'];
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$query->$where('r.fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$informe[] ='Fecha Desde: ' . $validos['fecha_desde'];
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
			if(in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)){
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
				$informe[] ='Oficina: ' . $filtros['oficina'];
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
				$query->$where('h.office= :oficina')->setParameter('oficina', $filtros['oficina']);
				$where='andWhere';
			}
			if(in_array('grupo', $validos)){
				$informe[] ='Grupo: ' . $filtros['grupo'];
				$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findByGrupo($filtros['grupo']);
				$like = array();
				foreach ($sitios as $sitio) {
					$like[] = $query->expr()->like('r.uRL', $query->expr()->literal('%'.$sitio->getUrl().'%'));
				}
				$query->$where(call_user_func_array(array($query->expr(), 'orX'), $like));
				$where = 'andWhere';
			}
			
			$resultados = $query->select('r.url, COUNT(r.id) AS cant')->addOrderBy('cant', 'DESC')->groupBy('r.url')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			return $this->render('Grupo3TallerUNLPInformeBundle:Informe:sitiosMostrar.html.twig',array(
			'resultados' => $resultados,
			'filtros' => $informe,
			));
		}
	}
	
	public function usuarioTraficoGenerarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$grupos = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();
		
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoGenerar.html.twig',array(
			'oficinas'	=> $oficinas,
			'grupos' => $grupos,
		));
	}
	
	public function usuarioTraficoMostrarAction(Request $request)
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
			$ok=false;
			$where = 'where';
			$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
			$informe = [];
			$informe[] = 'Cantidad :' . $filtros['cantidad'];
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$query->$where('r.fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$informe[] ='Fecha Desde: ' . $validos['fecha_desde'];
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
			if(in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)){
				$ok = true;
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
				$ok = true;
				$informe[] ='Oficina: ' . $filtros['oficina'];
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
				$query->$where('h.office= :oficina')->setParameter('oficina', $filtros['oficina']);
				$where='andWhere';
			}
			if(in_array('grupo', $validos)){
				$informe[] ='Grupo: ' . $filtros['grupo'];
				$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findByGrupo($filtros['grupo']);
				$like = array();
				foreach ($sitios as $sitio) {
					$like[] = $query->expr()->like('r.uRL', $query->expr()->literal('%'.$sitio->getUrl().'%'));
				}
				$query->$where(call_user_func_array(array($query->expr(), 'orX'), $like));
				$where = 'andWhere';
			}
			if($ok){
				$resultados = $query->select('i.id, COUNT(r.id) AS cant')->addOrderBy('cant', 'DESC')->groupBy('i.id')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			}else{
				$resultados = $query->select('i.id, COUNT(r.id) AS cant')->innerJoin('r.ip', 'i')->addOrderBy('cant', 'DESC')->groupBy('i.id')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
				$resultados = $query->select('i.id, COUNT(r.id) AS cant')->innerJoin('r.ip', 'i')->addOrderBy('cant', 'DESC')->groupBy('i.id')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			}
			var_dump($resultados);
			die();
			return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoMostrar.html.twig',array(
			'resultados' => $resultados,
			'filtros' => $informe,
			));
		}
	}
	
	
	public function usuarioTraficoDenegadoGenerarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$grupos = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();

		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoDenegadoGenerar.html.twig',array(
			'oficinas'	=> $oficinas,
			'grupos' => $grupos,
		));
	}
	
	public function usuarioTraficoDenegadoMostrarAction(Request $request)
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
			$where = 'where';
			$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
			$informe = [];
			$informe[] = 'Cantidad :' . $filtros['cantidad'];
			$ok=false;
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$query->$where('r.fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$informe[] ='Fecha Desde: ' . $validos['fecha_desde'];
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
			if(in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)){
				$ok=true;
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
				$ok=true;
				$informe[] ='Oficina: ' . $filtros['oficina'];
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
				$query->$where('h.office= :oficina')->setParameter('oficina', $filtros['oficina']);
				$where='andWhere';
			}
			
			$query->$where('r.denegado = True');
			$informe[] = 'Trafico Denegado: SI' ;
			
			if($ok){
				$resultados = $query->select('i.id, COUNT(r.id) AS cant')->addOrderBy('cant', 'DESC')->groupBy('i.id')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			}else{
				$resultados = $query->select('i.id, COUNT(r.id) AS cant')->innerJoin('r.ip', 'i')->addOrderBy('cant', 'DESC')->groupBy('i.id')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			}
			var_dump($resultados);
			die();
			return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoMostrar.html.twig',array(
			'resultados' => $resultados,
			'filtros' => $informe,
			));
		}
	}
	
	public function protocoloGenerarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$grupos = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();

		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:protocoloGenerar.html.twig',array(
			'oficinas'	=> $oficinas,
			'grupos'	=> $grupos,
		));
	}
	
	
	public function protocoloMostrarAction(Request $request)
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
			$where = 'where';
			$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
			$informe = [];
			$informe[] = 'Cantidad :' . $filtros['cantidad'];
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$query->$where('r.fecha <= :fecha_hasta')->setParameter('fecha_hasta', $filtros['fecha_hasta']);
				$informe[] ='Fecha Desde: ' . $filtros['fecha_desde'];
				$informe[] ='Fecha Hasta: ' . $filtros['fecha_hasta'];
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('r.fecha >= :fecha_desde')->setParameter('fecha_desde', $filtros['fecha_desde']);
				$where = 'andWhere';
				$informe[] ='Fecha Desde: ' . $validos['fecha_desde'];
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
			if(in_array('ip_desde', $validos) || in_array('ip_hasta', $validos)){
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
				$informe[] ='Oficina: ' . $filtros['oficina'];
				$query->innerJoin('r.ip', 'i')->innerJoin('i.host', 'h');
				$query->$where('h.office= :oficina')->setParameter('oficina', $filtros['oficina']);
				$where='andWhere';
			}
			if(in_array('grupo', $validos)){
				$informe[] ='Grupo: ' . $filtros['grupo'];
				$sitios = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findByGrupo($filtros['grupo']);
				$like = array();
				foreach ($sitios as $sitio) {
					$like[] = $query->expr()->like('r.uRL', $query->expr()->literal('%'.$sitio->getUrl().'%'));
				}
				$query->$where(call_user_func_array(array($query->expr(), 'orX'), $like));
				$where = 'andWhere';
			}
			
			$resultados = $query->select('r.protocolo, COUNT(r.id) AS cant')->addOrderBy('cant', 'DESC')->groupBy('r.protocolo')->setMaxResults($filtros['cantidad'])->getQuery()->getResult();
			return $this->render('Grupo3TallerUNLPInformeBundle:Informe:protocoloMostrar.html.twig',array(
			'resultados' => $resultados,
			'filtros' => $informe,
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
?>