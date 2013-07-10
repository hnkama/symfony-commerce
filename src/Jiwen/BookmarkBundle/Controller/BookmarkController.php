<?php

namespace Jiwen\BookmarkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Jiwen\BookmarkBundle\Entity\Bookmark;
use Jiwen\BookmarkBundle\Form\BookmarkType;

/**
 * Bookmark controller.
 *
 * @Route("/bookmark")
 */
class BookmarkController extends Controller
{

    /**
     * Lists all Bookmark entities.
     *
     * @Route("/", name="bookmark")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JiwenBookmarkBundle:Bookmark')->findAll();

        return 
		$this->render('JiwenBookmarkBundle:Bookmark:index.html.twig', 
			array(
				'entities' => $entities,
        ));
    }
    /**
     * Creates a new Bookmark entity.
     *
     * @Route("/", name="bookmark_create")
     * @Method("POST")
     * @Template("JiwenBookmarkBundle:Bookmark:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Bookmark();
        $form = $this->createForm(new BookmarkType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bookmark_show', array('id' => $entity->getId())));
        }

        return $this->render('JiwenBookmarkBundle:Bookmark:new.html.twig', 
			array(
				'entity' => $entity,
				'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Bookmark entity.
     *
     * @Route("/new", name="bookmark_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Bookmark();
        $form   = $this->createForm(new BookmarkType(), $entity);

        return $this->render('JiwenBookmarkBundle:Bookmark:new.html.twig', 
				array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Bookmark entity.
     *
     * @Route("/{id}", name="bookmark_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBookmarkBundle:Bookmark')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bookmark entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBookmarkBundle:Bookmark:show.html.twig', 
				array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Bookmark entity.
     *
     * @Route("/{id}/edit", name="bookmark_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBookmarkBundle:Bookmark')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bookmark entity.');
        }

        $editForm = $this->createForm(new BookmarkType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBookmarkBundle:Bookmark:edit.html.twig', 
			array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Bookmark entity.
     *
     * @Route("/{id}", name="bookmark_update")
     * @Method("PUT")
     * @Template("JiwenBookmarkBundle:Bookmark:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBookmarkBundle:Bookmark')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bookmark entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BookmarkType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bookmark_edit', array('id' => $id)));
        }

        return $this->render('JiwenBookmarkBundle:Bookmark:edit.html.twig', 
			array(
				'entity'      => $entity,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Bookmark entity.
     *
     * @Route("/{id}", name="bookmark_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JiwenBookmarkBundle:Bookmark')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bookmark entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bookmark'));
    }

    /**
     * Creates a form to delete a Bookmark entity by id.
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
