<?php

namespace Grupo3TallerUNLP\UsuarioRedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\UsuarioRedBundle\Entity\UsuarioRed;
use Grupo3TallerUNLP\UsuarioRedBundle\Form\UsuarioRedType;

/**
 * UsuarioRed controller.
 *
 */
class UsuarioRedController extends Controller
{

    /**
     * Lists all UsuarioRed entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->createQueryBuilder('u');

       	$pag = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->findOneById('1');
		$num = $pag->getPaginacion();
		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), $num);

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new UsuarioRed entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new UsuarioRed();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
			if(! is_numeric($entity->getDNI()) || ($entity->getDNI()<999999)) {
				$this->get('session')->getFlashBag()->add('error', 'El DNI debe ser un valor numerico y mayor a 999999');
				return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:new.html.twig', array(
					'entity' => $entity,
					'form'   => $form->createView(),
				));
			}		
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('usuariored'));
        }

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a UsuarioRed entity.
     *
     * @param UsuarioRed $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UsuarioRed $entity)
    {
        $form = $this->createForm(new UsuarioRedType(), $entity, array(
            'action' => $this->generateUrl('usuariored_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new UsuarioRed entity.
     *
     */
    public function newAction()
    {
        $entity = new UsuarioRed();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UsuarioRed entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el usuario buscado');
			return $this->redirect($this->generateUrl('usuariored'));
        }
		$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 5')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:show.html.twig', array(
            'entity'      => $entity,
			'plantilla' => $Plantilla,
        ));
    }

    /**
     * Finds and displays a UsuarioRed entity.
     *
     */
    public function showDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el usuario buscado');
				return $this->redirect($this->generateUrl('usuariored'));
        }

        $deleteForm = $this->createDeleteForm($id);
		$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 5')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:delete.html.twig', array(
            'entity'      => $entity,
			'plantilla' => $Plantilla,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UsuarioRed entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el usuario buscado');
				return $this->redirect($this->generateUrl('usuariored'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a UsuarioRed entity.
    *
    * @param UsuarioRed $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(UsuarioRed $entity)
    {
        $form = $this->createForm(new UsuarioRedType(), $entity, array(
            'action' => $this->generateUrl('usuariored_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing UsuarioRed entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el usuario buscado');
				return $this->redirect($this->generateUrl('usuariored'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {		
			if(! is_numeric($entity->getDNI()) || ($entity->getDNI()<999999)) {
				$this->get('session')->getFlashBag()->add('error', 'El DNI debe ser un valor numerico y mayor a 999999');
				return $this->redirect($this->generateUrl('usuariored_edit', array('id' => $id)));
			}
		
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('usuariored'));
        }

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a UsuarioRed entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed')->find($id);
            if (!$entity) {
               
				$this->get('session')->getFlashBag()->add('error', 'No se encontro el usuario buscado');
				return $this->redirect($this->generateUrl('usuariored'));
	        }
			$user = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->findOneByUsuarioRed($id);
			if($user){
				$this->get('session')->getFlashBag()->add('error', 'La operacion no pudo realizarse, el usuario tiene usuario del sistema asociado');
				return $this->redirect($this->generateUrl('usuariored'));
			}
			$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 5')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
			if($Plantilla){
				$this->get('session')->getFlashBag()->add('error', 'La operacion no pudo realizarse, el usuario tiene plantillas asociadas');
				return $this->redirect($this->generateUrl('usuariored'));
			}
            $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
        return $this->redirect($this->generateUrl('usuariored'));
    }

    /**
     * Creates a form to delete a UsuarioRed entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuariored_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
