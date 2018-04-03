<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;


interface ShoppingListWidgetToCustomerClientInterface
{
    /**
     * @return bool
     */
    public function isLoggedIn(): bool;
}
