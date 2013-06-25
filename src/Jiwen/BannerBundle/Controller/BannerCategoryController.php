<?php

namespace Jiwen\BannerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Jiwen\BannerBundle\Entity\BannerCategory;
use Jiwen\BannerBundle\Form\BannerCategoryType;

/**
 * BannerCategory controller.
 *
 */
class BannerCategoryController extends Controller
{

    /**
     * Lists all BannerCategory entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JiwenBannerBundle:BannerCategory')->findAll();

        return $this->render('JiwenBannerBundle:BannerCategory:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new BannerCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new BannerCategory();
        $form = $this->createForm(new BannerCategoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bannercategory_show', array('id' => $entity->getId())));
        }

        return $this->render('JiwenBannerBundle:BannerCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new BannerCategory entity.
     *
     */
    public function newAction()
    {
        $entity = new BannerCategory();
        $form   = $this->createForm(new BannerCategoryType(), $entity);

        return $this->render('JiwenBannerBundle:BannerCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a BannerCategory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:BannerCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannerCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBannerBundle:BannerCategory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing BannerCategory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:BannerCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannerCategory entity.');
        }

        $editForm = $this->createForm(new BannerCategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBannerBundle:BannerCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing BannerCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:BannerCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannerCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BannerCategoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bannercategory_edit', array('id' => $id)));
        }

        return $this->render('JiwenBannerBundle:BannerCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a BannerCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JiwenBannerBundle:BannerCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BannerCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bannercategory'));
    }

    /**
     * Creates a form to delete a BannerCategory entity by id.
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
