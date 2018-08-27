<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

class ShoppingListWidgetToShoppingListSessionClientBridge implements ShoppingListWidgetToShoppingListSessionClientInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected $shoppingListSessionClient;

    /**
     * @param \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface $shoppingListSessionClient
     */
    public function __construct($shoppingListSessionClient)
    {
        $this->shoppingListSessionClient = $shoppingListSessionClient;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListSessionClient->getCustomerShoppingListCollection();
    }
}
