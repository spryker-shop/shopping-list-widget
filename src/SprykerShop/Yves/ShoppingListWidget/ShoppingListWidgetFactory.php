<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListSessionClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\DataProvider\ShoppingListFromCartFormDataProvider;
use SprykerShop\Yves\ShoppingListWidget\Form\FormHandler\CreateFromCartHandler;
use SprykerShop\Yves\ShoppingListWidget\Form\FormHandler\CreateFromCartHandlerInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\ShoppingListFromCartForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetConfig getConfig()
 */
class ShoppingListWidgetFactory extends AbstractFactory
{
    public function createShoppingListFromCartFormDataProvider(): ShoppingListFromCartFormDataProvider
    {
        return new ShoppingListFromCartFormDataProvider($this->getShoppingListClient());
    }

    public function getShoppingListFromCartForm(?int $idQuote): FormInterface
    {
        $formDataProvider = $this->createShoppingListFromCartFormDataProvider();

        return $this->getFormFactory()
            ->create(
                ShoppingListFromCartForm::class,
                $formDataProvider->getData($idQuote),
                $formDataProvider->getOptions(),
            );
    }

    public function createCreateFromCartHandler(): CreateFromCartHandlerInterface
    {
        return new CreateFromCartHandler($this->getShoppingListClient(), $this->getCustomerClient());
    }

    public function getShoppingListClient(): ShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    public function getCustomerClient(): ShoppingListWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    public function getShoppingListSessionClient(): ShoppingListWidgetToShoppingListSessionClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST_SESSION);
    }

    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }
}
