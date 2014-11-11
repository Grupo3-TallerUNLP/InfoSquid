<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\PlantillaBundle\Entity\Filtro;
use Grupo3TallerUNLP\PlantillaBundle\Form\FiltroType;

/**
 * Filtro controller.
 *
 */
class FiltroController extends Controller
{

    /**
     * Lists all Filtro entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->findAll();

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Filtro entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Filtro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('filtro_show', array('id' => $entity->getId())));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Filtro entity.
     *
     * @param Filtro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Filtro $entity)
    {
        $form = $this->createForm(new FiltroType(), $entity, array(
            'action' => $this->generateUrl('filtro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Filtro entity.
     *
     */
    public function newAction()
    {
        $entity = new Filtro();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Filtro entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filtro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Filtro entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filtro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Filtro entity.
    *
    * @param Filtro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Filtro $entity)
    {
        $form = $this->createForm(new FiltroType(), $entity, array(
            'action' => $this->generateUrl('filtro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Filtro entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filtro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('filtro_edit', array('id' => $id)));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:Filtro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Filtro entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:Filtro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Filtro entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('filtro'));
    }

    /**
     * Creates a form to delete a Filtro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('filtro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
