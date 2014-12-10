<?php

namespace Grupo3TallerUNLP\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Grupo3TallerUNLP\UserBundle\Entity\User;
use Grupo3TallerUNLP\UserBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->createQueryBuilder('u');

        $pag = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->findOneById('1');
        $num = $pag->getPaginacion();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), $num);

        return array(
            'pagination' => $pagination,
        );
    }
    /**
     * Creates a new User entity.
     *
     * @Route("/", name="grupo3_taller_unlp_user_create")
     * @Method("POST")
     * @Template("Grupo3TallerUNLPUserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('Grupo3TallerUNLPUserBundle:User');

            // username unico
			$user = $repo->findOneByUsername($entity->getUsername());
				if (!$user) {
					$username=$entity->getUsername();
					if((strlen($username) >= 5) && (( preg_match('/^[\-\._A-Za-z0-9]+$/', $username)) && ( preg_match('/[A-Za-z]/', $username)))){
						// email unico
						$user = $repo->findOneByEmail($entity->getEmail());
						if (!$user) {
							// usuario red unico
							$user = $repo->findOneByUsuarioRed($entity->getUsuarioRed());
							if (!$user) {
								$pass = $entity->getPlainPassword();
								if(strlen($pass) <5){
									$this->get('session')->getFlashBag()->add('error', 'La contraseña debe contener como minimo 5 caracteres');
								}
								else{
								$em->persist($entity);
								$em->flush();

								$this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
								return $this->redirect($this->generateUrl('grupo3_taller_unlp_user'));
								}
							} else {
								$this->get('session')->getFlashBag()->add('error', 'Ya existe un usuario de sistema para ese usuario de red');
							}
						} else {
							$this->get('session')->getFlashBag()->add('error', 'Ya existe un usuario con ese email');
						}
					}else {
						$this->get('session')->getFlashBag()->add('error', 'El username debe tener como minimo 5 caracteres y al menos una letra');
						}
				} else {
					$this->get('session')->getFlashBag()->add('error', 'Ya existe un usuario con ese nombre de usuario');
				}
		}


        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action'   => $this->generateUrl('grupo3_taller_unlp_user_create'),
            'method'   => 'POST',
            'required' => true,
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="grupo3_taller_unlp_user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    public function showDeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPUserBundle:User:delete.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="grupo3_taller_unlp_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action'   => $this->generateUrl('grupo3_taller_unlp_user_update', array('id' => $entity->getId())),
            'method'   => 'PUT',
            'required' => false,
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_user_update")
     * @Method("PUT")
     * @Template("Grupo3TallerUNLPUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('Grupo3TallerUNLPUserBundle:User');
        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $user = $repo->findOneByEmail($entity->getEmail());
            if (!$user || $user->getId() == $entity->getId()) {
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'La operación se realizó con éxito');
                return $this->redirect($this->generateUrl('grupo3_taller_unlp_user'));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Ya existe un usuario con ese email');
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="grupo3_taller_unlp_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            if ($request->request->get('eliminar_usuariored', false)) {
                $usuariored = $entity->getUsuarioRed();
                $em->remove($usuariored);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grupo3_taller_unlp_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo3_taller_unlp_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
