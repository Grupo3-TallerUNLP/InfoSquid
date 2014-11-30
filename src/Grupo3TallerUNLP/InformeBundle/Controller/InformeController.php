<?php

namespace Grupo3TallerUNLP\InformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;
use Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed;
use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;


class InformeController extends Controller
{
    public function indexAction()
    {
        return $this->render('Grupo3TallerUNLPInformeBundle:Informe:index.html.twig');
    }

	public function generarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$plantillas = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->findByUsuariosistema($user);
		$oficinas = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		$usuarios = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->findAll();
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
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$valorfiltro = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->findByPlantilla($id);

		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarPlantilla.html.twig',array(
			'resultados' => $resultados,
		));
	}

	public function mostrarFiltroAction()
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//consultas

		return $this->render('Grupo3TallerUNLPInformeBundle:Informe:mostrarFiltro.html.twig',array(
			'resultados' => $resultados,
		));
	}





}
