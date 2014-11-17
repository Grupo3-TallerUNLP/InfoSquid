<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\PlantillaBundle\Entity\Plantilla;
use Grupo3TallerUNLP\PlantillaBundle\Form\PlantillaType;

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
		$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->findAll();
		return $entities;
	}
	
	private function listGrupos()
	{
		$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findAll();
		return $entities;
	}
	
	private function listSitios()
	{
		$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->findAll();
		return $entities;
	}
	
	private function listOficinas()
	{
		$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->findAll();
		return $entities;
	}
	
	private function listUsuarios()
	{
		$em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->findAll();
		return $entities;
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('plantilla', array('id' => $entity->getId())));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
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

        $form->add('submit', 'submit', array('label' => 'Update'));

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
            $em->flush();
			
			$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('plantilla', array('id' => $id)));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Plantilla:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
