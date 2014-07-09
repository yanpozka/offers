<?php

namespace Ganguera\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ganguera\FrontendBundle\Form\OfertaType;

/**
 * Oferta controller.
 *
 */
class OfertaController extends Controller {

    /**
     * Lists all Oferta entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontendBundle:Oferta')->findAll();

        return $this->render('BackendBundle:Oferta:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Oferta entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Oferta entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $editForm = $this->createForm(new OfertaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Oferta entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OfertaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Oferta:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Oferta entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontendBundle:Oferta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Oferta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('oferta'));
    }

    /**
     * Creates a form to delete a Oferta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
