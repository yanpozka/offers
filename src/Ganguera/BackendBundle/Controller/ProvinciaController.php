<?php

namespace Ganguera\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ganguera\FrontendBundle\Entity\Provincia;
use Ganguera\BackendBundle\Form\ProvinciaType;

/**
 * Provincia controller.
 *
 */
class ProvinciaController extends Controller
{

    /**
     * Lists all Provincia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontendBundle:Provincia')->findAll();

        return $this->render('BackendBundle:Provincia:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Provincia entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Provincia();
        $form = $this->createForm(new ProvinciaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('prov_show', array('id' => $entity->getId())));
        }

        return $this->render('BackendBundle:Provincia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Provincia entity.
     *
     */
    public function newAction()
    {
        $entity = new Provincia();
        $form   = $this->createForm(new ProvinciaType(), $entity);

        return $this->render('BackendBundle:Provincia:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Provincia entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Provincia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provincia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Provincia:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Provincia entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Provincia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provincia entity.');
        }

        $editForm = $this->createForm(new ProvinciaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Provincia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Provincia entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontendBundle:Provincia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provincia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProvinciaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('prov_edit', array('id' => $id)));
        }

        return $this->render('BackendBundle:Provincia:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Provincia entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontendBundle:Provincia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Provincia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('prov'));
    }

    /**
     * Creates a form to delete a Provincia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
