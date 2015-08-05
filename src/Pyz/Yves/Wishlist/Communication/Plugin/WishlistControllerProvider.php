<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Pyz\Yves\Wishlist\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Application\Communication\Plugin\YvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class WishlistControllerProvider extends YvesControllerProvider
{
    const ROUTE_WISHLIST = 'wishlist';
    const ROUTE_ADD = 'wishlist/add';
    const ROUTE_REMOVE = 'wishlist/remove';
    const ROUTE_REDUCE = 'wishlist/reduce';
    const ROUTE_INCREASE = 'wishlist/increase';

    /**
     * @param Application $app
     */
    protected function defineControllers(Application $app)
    {
        $this->createGetController('/wishlist', static::ROUTE_WISHLIST, 'Wishlist', 'Wishlist');

        $this->createGetController('/wishlist/add/{sku}', static::ROUTE_ADD, 'Wishlist', 'Wishlist', 'add')
            ->assert('sku', '[a-zA-Z0-9-_]+')
            ->convert('quantity', [$this, 'getQuantityFromRequest']);

        $this->createGetController('/wishlist/remove/{sku}', static::ROUTE_REMOVE, 'Wishlist', 'Wishlist', 'remove')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        $this->createGetController('/wishlist/reduce/{sku}', static::ROUTE_REDUCE, 'Wishlist', 'Wishlist', 'reduce')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        $this->createGetController('/wishlist/increase/{sku}', static::ROUTE_INCREASE, 'Wishlist', 'Wishlist', 'increase')
            ->assert('sku', '[a-zA-Z0-9-_]+');
    }

    /**
     * @param mixed $unusedParameter
     * @param Request $request
     *
     * @return int
     */
    public function getQuantityFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return (int) $request->request->get('quantity', 1);
        }

        return (int) $request->query->get('quantity', 1);
    }

}
