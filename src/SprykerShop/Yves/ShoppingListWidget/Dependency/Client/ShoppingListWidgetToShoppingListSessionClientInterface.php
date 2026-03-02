<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

interface ShoppingListWidgetToShoppingListSessionClientInterface
{
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer;
}
