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

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);

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
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('usuariored', array('id' => $entity->getId())));
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
            throw $this->createNotFoundException('Unable to find UsuarioRed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPUsuarioRedBundle:UsuarioRed:show.html.twig', array(
            'entity'      => $entity,
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
            throw $this->createNotFoundException('Unable to find UsuarioRed entity.');
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
            throw $this->createNotFoundException('Unable to find UsuarioRed entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
            return $this->redirect($this->generateUrl('usuariored', array('id' => $id)));
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
                throw $this->createNotFoundException('Unable to find UsuarioRed entity.');
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
