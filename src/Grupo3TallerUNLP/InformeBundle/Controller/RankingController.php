<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;

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
	
	public function sitiosMostrarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//consultas
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:sitiosMostrar.html.twig',array(
			'entity' => $entity,
		));
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
	
	public function usuarioTraficoMostrarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//consultas
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoMostrar.html.twig',array(
			'entity' => $entity,
		));
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
	
	public function usuarioTraficoDenegadoMostrarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//consultas
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:usuarioTraficoDenegadoMostrar.html.twig',array(
			'entity' => $entity,
		));
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
	
	public function protocoloMostrarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//consultas
		
		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:protocoloMostrar.html.twig',array(
			'entity' => $entity,
		));
	}
}
?>