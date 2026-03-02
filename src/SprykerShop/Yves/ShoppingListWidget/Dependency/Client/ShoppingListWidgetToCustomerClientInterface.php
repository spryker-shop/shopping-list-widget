<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface ShoppingListWidgetToCustomerClientInterface
{
    public function getCustomer(): ?CustomerTransfer;

    public function isLoggedIn(): bool;
}
