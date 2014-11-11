<?php

namespace Grupo3TallerUNLP\SitioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\SitioBundle\Entity\Sitio;
use Grupo3TallerUNLP\SitioBundle\Form\SitioType;

/**
 * Sitio controller.
 *
 */
class SitioController extends Controller
{

    /**
     * Lists all Sitio entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->createQueryBuilder('u');
	
		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);
		
        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new Sitio entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Sitio();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
            return $this->redirect($this->generateUrl('sitio', array('id' => $entity->getId())));
        }

        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Sitio entity.
     *
     * @param Sitio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sitio $entity)
    {
        $form = $this->createForm(new SitioType(), $entity, array(
            'action' => $this->generateUrl('sitio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Sitio entity.
     *
     */
    public function newAction()
    {
        $entity = new Sitio();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sitio entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sitio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sitio entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sitio entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Sitio entity.
    *
    * @param Sitio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sitio $entity)
    {
        $form = $this->createForm(new SitioType(), $entity, array(
            'action' => $this->generateUrl('sitio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Sitio entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sitio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
            return $this->redirect($this->generateUrl('sitio', array('id' => $id)));
        }

        return $this->render('Grupo3TallerUNLPSitioBundle:Sitio:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Sitio entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPSitioBundle:Sitio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sitio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
		$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
        return $this->redirect($this->generateUrl('sitio'));
    }

    /**
     * Creates a form to delete a Sitio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sitio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
