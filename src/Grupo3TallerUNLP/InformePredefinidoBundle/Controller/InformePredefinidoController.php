<?php

namespace Grupo3TallerUNLP\InformePredefinidoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\InformePredefinidoBundle\Entity\InformePredefinido;
use Grupo3TallerUNLP\InformePredefinidoBundle\Form\InformePredefinidoType;

/**
 * InformePredefinido controller.
 *
 */
class InformePredefinidoController extends Controller
{

    /**
     * Lists all InformePredefinido entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido')->createQueryBuilder('u');
		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:index.html.twig', array('pagination' => $pagination));
    }
    /**
     * Creates a new InformePredefinido entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new InformePredefinido();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

			$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('informepredefinido'));
        }

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a InformePredefinido entity.
     *
     * @param InformePredefinido $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InformePredefinido $entity)
    {
        $form = $this->createForm(new InformePredefinidoType(), $entity, array(
            'action' => $this->generateUrl('informepredefinido_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new InformePredefinido entity.
     *
     */
    public function newAction()
    {
        $entity = new InformePredefinido();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a InformePredefinido entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformePredefinido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing InformePredefinido entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformePredefinido entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a InformePredefinido entity.
    *
    * @param InformePredefinido $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(InformePredefinido $entity)
    {
        $form = $this->createForm(new InformePredefinidoType(), $entity, array(
            'action' => $this->generateUrl('informepredefinido_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing InformePredefinido entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformePredefinido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('informepredefinido'));
        }

        return $this->render('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a InformePredefinido entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPInformePredefinidoBundle:InformePredefinido')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find InformePredefinido entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
		$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
        return $this->redirect($this->generateUrl('informepredefinido'));
    }

    /**
     * Creates a form to delete a InformePredefinido entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('informepredefinido_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
