<?php

namespace Grupo3TallerUNLP\PlantillaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Grupo3TallerUNLP\PlantillaBundle\Entity\ValorFiltro;
use Grupo3TallerUNLP\PlantillaBundle\Form\ValorFiltroType;

/**
 * ValorFiltro controller.
 *
 */
class ValorFiltroController extends Controller
{

    /**
     * Lists all ValorFiltro entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->findAll();

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ValorFiltro entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ValorFiltro();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('valorfiltro_show', array('id' => $entity->getId())));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ValorFiltro entity.
     *
     * @param ValorFiltro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ValorFiltro $entity)
    {
        $form = $this->createForm(new ValorFiltroType(), $entity, array(
            'action' => $this->generateUrl('valorfiltro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ValorFiltro entity.
     *
     */
    public function newAction()
    {
        $entity = new ValorFiltro();
        $form   = $this->createCreateForm($entity);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ValorFiltro entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ValorFiltro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ValorFiltro entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ValorFiltro entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ValorFiltro entity.
    *
    * @param ValorFiltro $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ValorFiltro $entity)
    {
        $form = $this->createForm(new ValorFiltroType(), $entity, array(
            'action' => $this->generateUrl('valorfiltro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ValorFiltro entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ValorFiltro entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('valorfiltro_edit', array('id' => $id)));
        }

        return $this->render('Grupo3TallerUNLPPlantillaBundle:ValorFiltro:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ValorFiltro entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Grupo3TallerUNLPPlantillaBundle:ValorFiltro')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ValorFiltro entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('valorfiltro'));
    }

    /**
     * Creates a form to delete a ValorFiltro entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('valorfiltro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
