<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class CartToShoppingListController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_REFERER = 'referer';

    /**
     * @var string
     */
    protected const PARAM_ID_QUOTE = 'idQuote';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CART_NOT_AVAILABLE = 'shopping_list.cart.not_available';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS = 'shopping_list.cart.items_add.success';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_FAILED = 'shopping_list.cart.items_add.failed';

    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST_DETAILS
     *
     * @var string
     */
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createFromCartAction(Request $request)
    {
        $response = $this->executeCreateFromCartAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateFromCartAction(Request $request)
    {
        $idQuote = $request->get(static::PARAM_ID_QUOTE);
        $cartToShoppingListForm = $this->getFactory()
            ->getShoppingListFromCartForm($idQuote)
            ->handleRequest($request);

        if ($cartToShoppingListForm->isSubmitted() && $cartToShoppingListForm->isValid()) {
            $shoppingListTransfer = $this->getFactory()
                ->createCreateFromCartHandler()
                ->createShoppingListFromCart($cartToShoppingListForm);

            $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS);

            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST_DETAILS, [
                'idShoppingList' => $shoppingListTransfer->getIdShoppingList(),
            ]);
        }

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        $referer = $request->headers->get(static::PARAM_REFERER);

        return $this->redirectResponseExternal($referer);
    }
}
