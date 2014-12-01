<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
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
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:generarInforme.html.twig',array(
			'plantillas' => $plantillas,
			'oficinas'	=> $oficinas,
			'usuarios'	=> $usuarios,
			'grupos'	=> $grupos,
			'sitios'	=> $sitios,
		));
	}
	
	public function mostrarPlantillaAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$valorfiltro = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->findByPlantilla($id);
		
		//acomodar los if a valorfiltro, pero deberiamos seguir con la misma estructura
		
		$where = 'where';
		$query = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPInformeBundle:Request')->createQueryBuilder('r');
		if(in_array(1, $valorfiltro) ){
			$result = $today - $valorfiltro[1]['valor']; //ver
			$query->$where('fecha >= :fecha_desde')->setParameter('fecha_desde', $result);
			$where = 'andWhere';
			$query->$where('fecha <= :fecha_hasta')->setParameter('fecha_hasta', $valorfiltro[1]['valor']);
		if (in_array(2, $valorfiltro) && in_array(3, $valorfiltro)) {
			$query->$where('hora >= :hora_desde')->setParameter('hora_desde', $valorfiltro[2]['valor']);
			$where = 'andWhere';
			$query->$where('hora <= :hora_hasta')->setParameter('hora_hasta', $valorfiltro[3]['valor']);
		}elseif(in_array(2, $valorfiltro)){
			$query->$where('hora >= :hora_desde')->setParameter('hora_desde', $valorfiltro[2]['valor']);
			$where = 'andWhere';
		}elseif(in_array(3, $valorfiltro)){
			$query->$where('hora <= :hora_hasta')->setParameter('hora_hasta', $valorfiltro[3]['valor']);
			$where = 'andWhere';
		}
		if(in_array(6, $valorfilto)){
			$query->$where('ip <= :id_ip')->setParameter('id_ip', $valorfiltro[6]['valor']);
			$where = 'andWhere';
		}elseif(in_array('ip_desde', $validos) && in_array('ip_hasta', $validos)){
			//buscar las ip 
		}elseif(in_array('oficina', $validos)){
				//buscar las ip de esa oficina y consultar por ese rango.
					
		}elseif(in_array('usuario', $validos)){
				//falta aca
		}
		if(in_array('grupo', $validos)){
					//falta aca
		}elseif(in_array('sitio', $validos)){
				//falta aca
		}
		if(in_array('traficodenegado', $validos)){
			$query->$where('denegado = :denegado')->setParameter('denegado', 'true');
			$where = 'andWhere';
		}		
	
		}
	
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarPlantilla.html.twig',array(
			'resultados' => $resultados,
		));
	}
	
	public function mostrarFiltroAction(Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();
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
			if(in_array('fecha_desde', $validos) && in_array('fecha_hasta', $validos)){
				$query->$where('fecha >= :fecha_desde')->setParameter('fecha_desde', $validos['fecha_desde']);
				$where = 'andWhere';
				$query->$where('fecha <= :fecha_hasta')->setParameter('fecha_hasta', $validos['fecha_hasta']);
			}elseif (in_array('fecha_desde', $validos)){
				$query->$where('fecha >= :fecha_desde')->setParameter('fecha_desde', $validos['fecha_desde']);
				$where = 'andWhere';
			}elseif (in_array('fecha_hasta', $validos)) {
				$query->$where('fecha <= :fecha_hasta')->setParameter('fecha_hasta', $validos['fecha_hasta']);
				$where = 'andWhere';
			}
			if (in_array('hora_desde', $validos) && in_array('hora_hasta', $validos)) {
				$query->$where('hora >= :hora_desde')->setParameter('hora_desde', $validos['hora_desde']);
				$where = 'andWhere';
				$query->$where('hora <= :hora_hasta')->setParameter('hora_hasta', $validos['hora_hasta']);
			}elseif(in_array('hora_desde', $validos)){
				$query->$where('hora >= :hora_desde')->setParameter('hora_desde', $validos['hora_desde']);
				$where = 'andWhere';
			}elseif(in_array('hora_hasta', $validos)){
				$query->$where('hora <= :hora_hasta')->setParameter('hora_hasta', $validos['hora_hasta']);
				$where = 'andWhere';
			}
			if(in_array('ip', $validos)){
				$id_ip = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPHostBundle:IPAddress')->findBy(array ('field1' => $validos['ip'][0], 'field2' => $validos['ip'][1], 'field2' => $validos['ip'][2], 'field3' => $validos['ip'][3]));
				$query->$where('ip <= :id_ip')->setParameter('id_ip', $validos['ip']);
				$where = 'andWhere';
			}elseif(in_array('ip_desde', $validos) && in_array('ip_hasta', $validos)){
				
			}elseif(in_array('oficina', $validos)){
					//buscar las ip de esa oficina y consultar por ese rango.
						
			}elseif(in_array('usuario', $validos)){
					//falta aca
			}
			if(in_array('grupo', $validos)){
						//falta aca
			}elseif(in_array('sitio', $validos)){
					//falta aca
			}
			if(in_array('traficodenegado', $validos)){
				$query->$where('denegado = :denegado')->setParameter('denegado', 'true');
				$where = 'andWhere';
			}
			
			
			
		
		}
  	
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarFiltro.html.twig',array(
			'resultados' => $resultados,
		));
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
			if (in_array('fecha_desde', $validos) && !preg_match('/^\d\d\-\d\d\-\d\d\d\d$/', $filtros['fecha_desde'])) {
				return 'La fecha desde debe tener un formato dd-mm-aaaa';
			} elseif (in_array('fecha_hasta', $validos) && !preg_match('/^\d\d\-\d\d\-\d\d\d\d$/', $filtros['fecha_hasta'])) {
				return 'La fecha hasta debe tener un formato dd-mm-aaaa';
			} elseif (in_array('hora_desde', $validos) && !preg_match('/^\d\d\:\d\d$/', $filtros['hora_desde'])) {
				return 'La hora desde debe tener el formato hh:mm';
			} elseif (in_array('hora_hasta', $validos) && !preg_match('/^\d\d\:\d\d$/', $filtros['hora_hasta'])) {
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
