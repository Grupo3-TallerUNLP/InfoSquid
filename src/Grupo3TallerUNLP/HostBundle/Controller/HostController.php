<?php

namespace Grupo3TallerUNLP\HostBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Grupo3TallerUNLP\HostBundle\Entity\Host;
use Grupo3TallerUNLP\HostBundle\Entity\IPAddress;
use Grupo3TallerUNLP\HostBundle\Form\HostType;

/**
 * Host controller.
 *
 * @Route("/admin/host")
 */
class HostController extends Controller
{

    /**
     * Lists all Host entities.
     *
     * @Route("/", name="grupo3_taller_unlp_admin_host")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPHostBundle:Host')->createQueryBuilder('h');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), 4);

        return array(
            'pagination' => $pagination,
        );
    }
    /**
     * Creates a new Host entity.
     *
     * @Route("/", name="grupo3_taller_unlp_admin_host_create")
     * @Method("POST")
     * @Template("Grupo3TallerUNLPHostBundle:Host:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Host();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->setIPAddress($entity)) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
                return $this->redirect($this->generateUrl('grupo3_taller_unlp_admin_host'));
            }
        }

        $this->get('session')->getFlashBag()->add('error', 'Ya existe un host con la dirección IP ingresada');

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new IPAddress or retrieve an existing one
     * and sets the IPAddress id to the Host
     *
     * @param Host $entity The entity
     */
    private function setIPAddress(Host $entity)
    {
        $repository = $this
            ->getDoctrine()
            ->getRepository('Grupo3TallerUNLPHostBundle:IPAddress')
            ;
        $ipAddress = $repository->findOneBy(array(
            'field1' => $entity->getIpAddressField1(),
            'field2' => $entity->getIpAddressField2(),
            'field3' => $entity->getIpAddressField3(),
            'field4' => $entity->getIpAddressField4(),
        ));
        if (!$ipAddress) {
            $ipAddress = new IPAddress();
            $ipAddress->setField1($entity->getIpAddressField1());
            $ipAddress->setField2($entity->getIpAddressField2());
            $ipAddress->setField3($entity->getIpAddressField3());
            $ipAddress->setField4($entity->getIpAddressField4());
            $this->getDoctrine()->getManager()->persist($ipAddress);
        } else {
            $host = $this
                ->getDoctrine()
                ->getRepository('Grupo3TallerUNLPHostBundle:Host')
                ->findOneByIpAddress($ipAddress->getId())
                ;

            if ($host && $host->getId() != $entity->getId()) {
                return false;
            }
        }
        $entity->setIpAddress($ipAddress);
        return true;
    }

    /**
     * Creates a form to create a Host entity.
     *
     * @param Host $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Host $entity)
    {
        $form = $this->createForm(new HostType(), $entity, array(
            'action' => $this->generateUrl('grupo3_taller_unlp_admin_host_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Host entity.
     *
     * @Route("/new", name="grupo3_taller_unlp_admin_host_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Host();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Host entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_admin_host_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPHostBundle:Host')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Host entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Host entity.
     *
     * @Route("/{id}/edit", name="grupo3_taller_unlp_admin_host_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPHostBundle:Host')->find($id);
        $ipAddress = $em->getRepository('Grupo3TallerUNLPHostBundle:IPAddress')->find($entity->getIpAddress());

        if (!$entity || !$ipAddress) {
            throw $this->createNotFoundException('Unable to find Host entity.');
        }

        $entity->setIpAddressField1($ipAddress->getField1());
        $entity->setIpAddressField2($ipAddress->getField2());
        $entity->setIpAddressField3($ipAddress->getField3());
        $entity->setIpAddressField4($ipAddress->getField4());

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Host entity.
    *
    * @param Host $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Host $entity)
    {
        $form = $this->createForm(new HostType(), $entity, array(
            'action' => $this->generateUrl('grupo3_taller_unlp_admin_host_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Host entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_admin_host_update")
     * @Method("PUT")
     * @Template("Grupo3TallerUNLPHostBundle:Host:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPHostBundle:Host')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Host entity.');
        }

        $entity->setIpAddressField1($request->request->get('ipAddress_field1'));
        $entity->setIpAddressField2($request->request->get('ipAddress_field2'));
        $entity->setIpAddressField3($request->request->get('ipAddress_field3'));
        $entity->setIpAddressField4($request->request->get('ipAddress_field4'));

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($this->setIPAddress($entity)) {
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
                return $this->redirect($this->generateUrl('grupo3_taller_unlp_admin_host'));
            }
        }

        $this->get('session')->getFlashBag()->add('error', 'Ya existe un host con la dirección IP ingresada');

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Host entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_admin_host_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPHostBundle:Host')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Host entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grupo3_taller_unlp_admin_host'));
    }

    /**
     * Creates a form to delete a Host entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo3_taller_unlp_admin_host_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
