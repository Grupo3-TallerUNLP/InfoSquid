<?php

namespace Grupo3TallerUNLP\OficinaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\OficinaBundle\Entity\Oficina;
use Grupo3TallerUNLP\OficinaBundle\Form\OficinaType;

/**
 * Oficina controller.
 *
 */
class OficinaController extends Controller
{

    /**
     * Lists all Oficina entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->createQueryBuilder('u');

		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);

        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new Oficina entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Oficina();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
            return $this->redirect($this->generateUrl('oficina'));
        }

        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Oficina entity.
     *
     * @param Oficina $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Oficina $entity)
    {
        $form = $this->createForm(new OficinaType(), $entity, array(
            'action' => $this->generateUrl('oficina_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Oficina entity.
     *
     */
    public function newAction()
    {
        $entity = new Oficina();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Oficina entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oficina entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Oficina entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oficina entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Oficina entity.
    *
    * @param Oficina $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Oficina $entity)
    {
        $form = $this->createForm(new OficinaType(), $entity, array(
            'action' => $this->generateUrl('oficina_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Oficina entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oficina entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');

            return $this->redirect($this->generateUrl('oficina'));
        }


        return $this->render('Grupo3TallerUNLPOficinaBundle:Oficina:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Oficina entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPOficinaBundle:Oficina')->find($id);
            $user = $entity->getDirector();
			$hosts = $entity->getHosts();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Oficina entity.');
            }
			elseif (($user) or ($hosts)) {
                $this->get('session')->getFlashBag()->add('error', 'La operacion no pudo realizarse, la oficina tiene usuario de red o host asociados');
				return $this->redirect($this->generateUrl('oficina'));
			}
			else{

				$em->remove($entity);
				$em->flush();
				$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
				return $this->redirect($this->generateUrl('oficina'));
			}
			}
        }




    /**
     * Creates a form to delete a Oficina entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('oficina_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
