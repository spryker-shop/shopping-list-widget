<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListSubtotalWidget extends AbstractWidget
{
    protected const PARAMETER_SHOPPING_LIST_SUBTOTAL = 'shoppingListSubtotal';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItems
     */
    public function __construct(array $shoppingListItems)
    {
        $this->addSubtotalParameter($shoppingListItems);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListSubtotalWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-subtotal/shopping-list-subtotal.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $shoppingListItems
     *
     * @return void
     */
    protected function addSubtotalParameter(array $shoppingListItems): void
    {
        $this->addParameter(static::PARAMETER_SHOPPING_LIST_SUBTOTAL, $this->getShoppingListClient()->calculateShoppingListSubtotal($shoppingListItems));
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface
     */
    protected function getShoppingListClient(): ShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getFactory()->getShoppingListClient();
    }
}
