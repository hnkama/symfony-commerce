<?php

namespace Jiwen\BannerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Jiwen\BannerBundle\Entity\Banner;
use Jiwen\BannerBundle\Form\BannerType;
use Jiwen\BannerBundle\Form\Filter\BannerFilterType;

/**
 * Banner controller.
 *
 */
class BannerController extends Controller
{

    /**
     * Lists all Banner entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('JiwenBannerBundle:Banner');

		$form = $this->get('form.factory')->create(new BannerFilterType($em));
		if ($this->get('request')->query->has('jiwen_banner_filter')) {
            // bind values from the request
            $form->bindRequest($this->get('request'));

            // initliaze a query builder
            $filterBuilder = $repo
					->createQueryBuilder('e');

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);

            // now look at the DQL =)
			$entities = $filterBuilder->getQuery()->getResult();
        } else {
			$entities = $repo->findAll();
		}

        return $this->render('JiwenBannerBundle:Banner:index.html.twig', array(
            'entities' => $entities,
			'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Banner entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Banner();
        $form = $this->createForm(new BannerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banner_show', array('id' => $entity->getId())));
        }

        return $this->render('JiwenBannerBundle:Banner:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

	public function filterAction()
	{
		$entity = new Banner();
        $form   = $this->createForm(new BannerFilterType(), $entity);

		return $this->render('JiwenBannerBundle:Banner:filter.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
		));
	}

    /**
     * Displays a form to create a new Banner entity.
     *
     */
    public function newAction()
    {
        $entity = new Banner();
        $form   = $this->createForm(new BannerType(), $entity);

        return $this->render('JiwenBannerBundle:Banner:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Banner entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBannerBundle:Banner:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Banner entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $editForm = $this->createForm(new BannerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JiwenBannerBundle:Banner:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Banner entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JiwenBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BannerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banner_edit', array('id' => $id)));
        }

        return $this->render('JiwenBannerBundle:Banner:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

	public function renderBannerAction($category, $limit, $template, $taxon = null)
	{
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('JiwenBannerBundle:Banner')->findTopBanner($category, $limit, $taxon);
		$category = $em->getRepository('JiwenBannerBundle:BannerCategory')->find($category);
        return $this->render('JiwenBannerBundle:Banner:'.$template.'.twig', array(
            'entity'      => $entity,
			'category' => $category,
        ));
	}
    /**
     * Deletes a Banner entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        $request = $this->get('request');
        if ($request->getMethod() == 'DELETE') {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('JiwenBannerBundle:Banner')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Banner entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('banner'));
    }

    /**
     * Creates a form to delete a Banner entity by id.
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
