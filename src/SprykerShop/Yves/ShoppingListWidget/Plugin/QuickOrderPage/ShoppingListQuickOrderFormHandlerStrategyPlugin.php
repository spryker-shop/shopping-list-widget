<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListQuickOrderFormHandlerStrategyPlugin extends AbstractPlugin implements QuickOrderFormHandlerStrategyPluginInterface
{
    /**
     * @see \SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS
     */
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';

    protected const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const PARAM_ADD_TO_SHOPPING_LIST = 'addToShoppingList';


    /**
     * {@inheritdoc}
     * - Returns true if "add to shopping list" button was pressed.
     *
     * @api
     *
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return bool
     */
    public function isApplicable(QuickOrderTransfer $quickOrderTransfer, array $params): bool
    {
        return !empty($params[static::PARAM_ADD_TO_SHOPPING_LIST]);
    }

    /**
     * {@inheritdoc}
     * - Adds products to shopping list.
     * - Returns with a route if all items were successfully added.
     * - Returns null in case of error.
     *
     * @api
     *
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return RouteTransfer|null
     */
    public function execute(QuickOrderTransfer $quickOrderTransfer, array $params): ?RouteTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $this->assertCustomerTransfer($customerTransfer);

        $shoppingListTransfer = $this->mapShoppingListTransfer($customerTransfer, $quickOrderTransfer,  $params);
        $shoppingListResponseTransfer = $this->getFactory()
                    ->getShoppingListClient()
                    ->addItems($shoppingListTransfer);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            return null;
        }

        return $this->createRedirectRoute($shoppingListResponseTransfer);
    }

    /**
     * @param ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return RouteTransfer
     */
    protected function createRedirectRoute(ShoppingListResponseTransfer $shoppingListResponseTransfer): RouteTransfer
    {
        return (new RouteTransfer())
            ->setRoute(static::ROUTE_SHOPPING_LIST_DETAILS)
            ->setParameters(
                [
                    static::PARAM_ID_SHOPPING_LIST => $shoppingListResponseTransfer->getShoppingList()->getIdShoppingList(),
                ]
            );
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function assertCustomerTransfer(CustomerTransfer $customerTransfer): void
    {
        $customerTransfer
            ->requireCustomerReference()
            ->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
                ->requireIdCompanyUser();
    }

    /**
     * @param CustomerTransfer $customerTransfer
     * @param QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return ShoppingListTransfer
     */
    protected function mapShoppingListTransfer(
        CustomerTransfer $customerTransfer,
        QuickOrderTransfer $quickOrderTransfer,
        array $params
    ): ShoppingListTransfer {
        $shoppingListItems = $this->mapShoppingListItems($quickOrderTransfer->getItems());

        return (new ShoppingListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setIdShoppingList((int)$params[static::PARAM_ID_SHOPPING_LIST])
            ->setItems($shoppingListItems);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuickOrderItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[]
     */
    protected function mapShoppingListItems(ArrayObject $itemTransfers): ArrayObject
    {
        $shoppingListItems = new ArrayObject();
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getSku()) {
                continue;
            }

            $shoppingListItems->append((new ShoppingListItemTransfer())
                ->setSku($itemTransfer->getSku())
                ->setQuantity($itemTransfer->getQty()));
        }

        return $shoppingListItems;
    }
}
