<?php

namespace Grupo3TallerUNLP\GrupoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\GrupoBundle\Entity\Grupo;
use Grupo3TallerUNLP\GrupoBundle\Form\GrupoType;
use Grupo3TallerUNLP\ConfiguracionBundle\Entity\Configuracion;
/**
 * Grupo controller.
 *
 */
class GrupoController extends Controller
{

    /**
     * Lists all Grupo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->createQueryBuilder('u');
		$pag = $em->getRepository('Grupo3TallerUNLPConfiguracionBundle:Configuracion')->findOneById('1');
		$num = $pag->getPaginacion();
		$paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $this->get('request')->query->get('page', 1), $num);

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    /**
     * Creates a new Grupo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Grupo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
		$grupo = $this->getDoctrine()->getManager()->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->findByNombre($entity->getNombre());
        if ($form->isValid()) {
			if (!$grupo){
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
				return $this->redirect($this->generateUrl('grupo'));
			}
			else{
				$this->get('session')->getFlashBag()->add('error', 'El nombre del grupo ya existe');
				return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:new.html.twig', array(
				'entity' => $entity,
				'form'   => $form->createView(),
				));
			}
        }

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Grupo entity.
     *
     * @param Grupo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grupo $entity)
    {
        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     */
    public function newAction()
    {
        $entity = new Grupo();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Grupo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }
		$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 9')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:delete.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
			'plantilla' => $Plantilla,
        ));
    }
	
		public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el grupo buscado');
			return $this->redirect($this->generateUrl('grupo'));
        }
		$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 9')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:show.html.twig', array(
            'entity'      => $entity,
			'plantilla' => $Plantilla,
        ));
    }

    /**
     * Displays a form to edit an existing Grupo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($id);

        if (!$entity) {
           $this->get('session')->getFlashBag()->add('error', 'No se encontro el grupo buscado');
			return $this->redirect($this->generateUrl('grupo'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Grupo entity.
    *
    * @param Grupo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grupo $entity)
    {
        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Grupo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('error', 'No se encontro el grupo buscado');
				return $this->redirect($this->generateUrl('grupo'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
		$grupo = $em->getRepository('Grupo3TallerUNLPGrupoBundle:grupo')->findOneByNombre($entity->getNombre());

        if ($editForm->isValid()) {
			if(!$grupo || $entity->getId() == $grupo->getId()){
				$em->flush();
				$this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
				return $this->redirect($this->generateUrl('grupo'));
			}
			else{
				$this->get('session')->getFlashBag()->add('error', 'El nombre del grupo ya existe');
				return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:edit.html.twig', array(
					'entity' => $entity,
					'edit_form'   => $editForm->createView(),
				));
			}
        }

        return $this->render('Grupo3TallerUNLPGrupoBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Grupo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPGrupoBundle:Grupo')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }
			$Plantilla = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Plantilla')->createQueryBuilder('p')->innerJoin('p.valorfiltro','v')->innerJoin('v.filtro','f')->where('f.id = 9')->andWhere('v.valor = :id')->setParameter('id', $id)->getQuery()->getResult();
			if($Plantilla){
				$this->get('session')->getFlashBag()->add('error', 'La operacion no puede realizarse ya que tiene plantillas asociadas');
				return $this->redirect($this->generateUrl('grupo'));
			}
		   $em->remove($entity);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success', 'La operacion se realizo con exito');
        return $this->redirect($this->generateUrl('grupo'));
    }

    /**
     * Creates a form to delete a Grupo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
}
