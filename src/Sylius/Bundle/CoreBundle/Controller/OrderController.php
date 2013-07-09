<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends ResourceController
{
    /**
     * Render order filter form.
     */
    public function filterFormAction(Request $request)
    {
        $form = $this->getFormFactory()->createNamed('criteria', 'sylius_order_filter');

        return $this->renderResponse('SyliusWebBundle:Backend/Order:filterForm.html', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByUserAction(Request $request, $id)
    {
        $config = $this->getConfiguration();
        $sorting = $config->getSorting();

        $user = $this->get('sylius.repository.user')
            ->findOneById($id);

        if (!isset($user)) {
            throw new NotFoundHttpException('Requested user does not exist');
        }

        $paginator = $this
            ->getRepository()
            ->createByUserPaginator($user, $sorting);

        $paginator->setCurrentPage($request->get('page', 1), true, true);
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('SyliusWebBundle:Backend/Order:indexByUser.html', array(
            'user' => $user,
            'orders' => $paginator
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function accountIndexByUserAction(Request $request, $id)
    {
        $config = $this->getConfiguration();
        $sorting = $config->getSorting();

        $user = $this->get('sylius.repository.user')
            ->findOneById($id);

        if (!isset($user)) {
            throw new NotFoundHttpException('Requested user does not exist');
        }

        $paginator = $this
            ->getRepository()
            ->createByUserPaginator($user, $sorting);

        $paginator->setCurrentPage($request->get('page', 1), true, true);
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('Frontend/Account:accountIndexByUser.html', array(
            'user' => $user,
            'orders' => $paginator
        ));
    }

	public function orderHistoryAction()
	{
        return $this->renderResponse('SyliusWebBundle:Frontend/Account:indexByUser.html');
	}

    private function getFormFactory()
    {
        return $this->get('form.factory');
    }

    /**
     * Get single resource by its identifier.
     */
    public function showAction()
    {
        $config = $this->getConfiguration();
		$intentions = 'unknown';  
 		$csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken($intentions);
        $form = $this->createDeleteForm();


        $view =  $this
            ->view()
            ->setTemplate($config->getTemplate('show.html'))
            ->setTemplateVar($config->getResourceName())
            ->setData(array(
				'order' => $this->findOr404(),
				'csrfToken' => $csrfToken,
				'form' => $form,
			))
        ;

        return $this->handleView($view);
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->getForm()
        ;
    }
}
