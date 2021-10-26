<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ShoppingListWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\ShoppingListWidget\Plugin\Router\ShoppingListWidgetRouteProviderPlugin::ROUTE_NAME_ADD_ITEM} instead.
     *
     * @var string
     */
    protected const ROUTE_ADD_ITEM = 'shopping-list/add-item';

    /**
     * @var string
     */
    public const ROUTE_NAME_ADD_ITEM = 'shopping-list/add-item';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ShoppingListWidget\Plugin\Router\ShoppingListWidgetRouteProviderPlugin::ROUTE_NAME_CART_TO_SHOPPING_LIST} instead.
     *
     * @var string
     */
    protected const ROUTE_CART_TO_SHOPPING_LIST = 'shopping-list/create-from-cart';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_TO_SHOPPING_LIST = 'shopping-list/create-from-cart';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAddItemRoute($routeCollection);
        $routeCollection = $this->addCreateShoppingListFromCartRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-item', 'ShoppingListWidget', 'ShoppingListWidget', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_ADD_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCreateShoppingListFromCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/create-from-cart', 'ShoppingListWidget', 'CartToShoppingList', 'createFromCartAction');
        $routeCollection->add(static::ROUTE_NAME_CART_TO_SHOPPING_LIST, $route);

        return $routeCollection;
    }
}
