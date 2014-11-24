<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;
use Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro;
use Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro;
use Grupo3TallerUNLP\PlantillaBundle\Form\PlantillaType;

use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;
use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\UsuarioBundle\Entity\Usuario;

/**
 * Plantilla controller.
 *
 */
class PlantillaController extends Controller
{

    /**
     * Lists all Plantilla entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->findAll();
		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new Plantilla entity.
     *
     */

	private function listFiltros()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->findAll();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function listGrupos()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function listSitios()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findAll();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function listOficinas()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function listUsuarios()
	{
		$choices = array();
		$table = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->findAll();

		foreach($table as $t) {
			$choices[$t->getId()] = $t;
		}
		return $choices;
	}

	private function lists()
	{
		$filtros = $this->listFiltros();
		$grupos = $this->listGrupos();
		$sitios = $this->listSitios();
		$oficinas = $this->listOficinas();
		$usuarios = $this->listUsuarios();
		$datos = array ('filtros' => $filtros,
					'grupos' => $grupos,
					'sitios' => $sitios,
					'oficinas' => $oficinas,
					'usuarios' => $usuarios);
		return $datos;
	}

    public function createAction(Request $request)
    {

        $entity = new Plantilla();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
			$filtros = $request->request->get('filtros');
			$validos = array();
			$error = $this->validarFiltros($filtros, $validos);
			if (!is_null($error)) {
				$this->get('session')->getFlashBag()->add('error', $error);
			} else {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				foreach ($validos as $valido){
					$valorFiltro = new ValorFiltro();
					$valorFiltro->setPlantilla($entity);
					$valorFiltro->setFiltro($em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($valido));
					if (is_array($filtros[$valido])) {
						$valorFiltro->setValor(implode('.', $filtros[$valido]));
					} else {
						$valorFiltro->setValor($filtros[$valido]);
					}
					$em->persist($valorFiltro);
				}
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
				return $this->redirect($this->generateUrl('plantilla'));
			}
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
			'datos'  => $this->lists(),


        ));
    }


    /**
     * Creates a form to create a Plantilla entity.
     *
     * @param Plantilla $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Plantilla $entity)
    {
        $form = $this->createForm(new PlantillaType(), $entity, array(
            'action' => $this->generateUrl('plantilla_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Plantilla entity.
     *
     */
    public function newAction()
    {
        $entity = new Plantilla();
        $form   = $this->createCreateForm($entity);
		//$filtro= $this->lists();
        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
			'datos'  => $this->lists(),
        ));
    }

    /**
     * Finds and displays a Plantilla entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Plantilla entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
			'datos'  => $this->lists(),
			));
    }

    /**
    * Creates a form to edit a Plantilla entity.
    *
    * @param Plantilla $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Plantilla $entity)
    {
        $form = $this->createForm(new PlantillaType(), $entity, array(
            'action' => $this->generateUrl('plantilla_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Plantilla entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantilla entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
			$filtros = $request->request->get('filtros');
			$validos = array();
			$error = $this->validarFiltros($filtros, $validos);
			if (!is_null($error)) {
				$this->get('session')->getFlashBag()->add('error', $error);
			} else {
				$valorfiltros = $entity->getValorfiltro();
				foreach ($valorfiltros as $valorfiltro){
					$idFiltro = $valorfiltro->getFiltro()->getId();
					if (in_array($idFiltro, $validos)){
						if (is_array($filtros[$idFiltro])) {
							$valorfiltro->setValor(implode('.', $filtros[$idFiltro]));
						}else{
							$valorfiltro->setValor($filtros[$idFiltro]);
						}
						unset($validos[$idFiltro]);
					}
					else{
						$entity->removeValorfiltro($valorfiltro);
						$em->remove($valorfiltro);
					}
				}
				foreach ($validos as $valido){
					$valorFiltro = new ValorFiltro();
					$valorFiltro->setPlantilla($entity);
					$valorFiltro->setFiltro($em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($valido));
					if (is_array($filtros[$valido])) {
						$valorFiltro->setValor(implode('.', $filtros[$valido]));
					} else {
						$valorFiltro->setValor($filtros[$valido]);
					}
					$em->persist($valorFiltro);
				}
				$em->persist($entity);
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
				return $this->redirect($this->generateUrl('plantilla'));
			}
		}
        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
			'datos'  => $this->lists(),
        ));
	}
    /**
     * Deletes a Plantilla entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plantilla entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

		$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
        return $this->redirect($this->generateUrl('plantilla'));
    }

    /**
     * Creates a form to delete a Plantilla entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plantilla_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

	/**
	 * Comprueba que haya al menos un filtro y que los enviados sean válidos
	 */
	private function validarFiltros($filtros, &$validos)
	{
		$ok = false;
		$validos = array();
		foreach ($filtros as $id => $f){
			if(is_array($f)){
				if ((!empty($f[0]) || $f[0]=='0') && (!empty($f[1]) || $f[1]=='0') && (!empty($f[2]) || $f[2]=='0') && (!empty($f[3]) || $f[3]=='0')) {
					$ok = true;
					$validos[$id] = $id;
				}
			} else{
				If (!empty ($f)){
					$ok=true;
					$validos[$id] = $id;
				}
			}
		}
		if(!$ok){
			return 'Debe completar al menos un filtro';
		} else {
			if (in_array(1, $validos) && !preg_match('/^\d+$/', $filtros[1])) {
				return 'El rango de días debe ser un número entero';
			} elseif (in_array(2, $validos) && !preg_match('/^\d\d\:\d\d$/', $filtros[2])) {
				return 'La hora desde debe tener el formato hh:mm';
			} elseif (in_array(3, $validos) && !preg_match('/^\d\d\:\d\d$/', $filtros[3])) {
				return 'La hora hasta debe tener el formato hh:mm';
			} elseif (
				in_array(6, $validos)
				&& (is_int($filtros[6][0]) && $filtros[6][0] >= 0 && $filtros[6][0] <= 255)
				&& (is_int($filtros[6][1]) && $filtros[6][1] >= 0 && $filtros[6][1] <= 255)
				&& (is_int($filtros[6][2]) && $filtros[6][2] >= 0 && $filtros[6][2] <= 255)
				&& (is_int($filtros[6][3]) && $filtros[6][3] >= 0 && $filtros[6][3] <= 255)
			) {
				return 'La IP debe estar compuesta de cuatro campos de 0 a 255 cada uno';
			} elseif (
				in_array(7, $validos)
				&& (is_int($filtros[7][0]) && $filtros[7][0] >= 0 && $filtros[7][0] <= 255)
				&& (is_int($filtros[7][1]) && $filtros[7][1] >= 0 && $filtros[7][1] <= 255)
				&& (is_int($filtros[7][2]) && $filtros[7][2] >= 0 && $filtros[7][2] <= 255)
				&& (is_int($filtros[7][3]) && $filtros[7][3] >= 0 && $filtros[7][3] <= 255)
			) {
				return 'La IP desde debe estar compuesta de cuatro campos de 0 a 255 cada uno';
			} elseif (
				in_array(8, $validos)
				&& (is_int($filtros[8][0]) && $filtros[8][0] >= 0 && $filtros[8][0] <= 255)
				&& (is_int($filtros[8][1]) && $filtros[8][1] >= 0 && $filtros[8][1] <= 255)
				&& (is_int($filtros[8][2]) && $filtros[8][2] >= 0 && $filtros[8][2] <= 255)
				&& (is_int($filtros[8][3]) && $filtros[8][3] >= 0 && $filtros[8][3] <= 255)
			) {
				return 'La IP hasta debe estar compuesta de cuatro campos de 0 a 255 cada uno';
			} else {
				return null;
			}
		}
	}
}
