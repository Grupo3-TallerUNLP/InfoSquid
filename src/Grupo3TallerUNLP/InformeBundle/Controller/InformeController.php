<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;

class InformeController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Grupo3TallerUNLPInformeBundle:Informe:index.html.twig', array('name' => $name));
    }
	
	public function generarAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$plantillas = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->findByUsuariosistema($user);
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$usuarios = $em->getRepository('Grupo3TallerUNLPUsuarioRed:UsuarioRed')->findAll();
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
	
}
