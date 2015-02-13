<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Router\Provider\Pimple\RouterServiceProvider;
use Pimple\Container;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function testServiceProvider()
    {
        $container = new Container();
        $container->register(new RouterServiceProvider());
        $this->assertInstanceOf('Mendo\Router\RouterCollection', $container['router.collection']);
        $this->assertInstanceOf('Mendo\Router\RequestMatcher', $container['router.requestMatcher']);
        $container['router.urlMaker.context'] = null;
        $this->assertInstanceOf('Mendo\Router\UrlMaker', $container['router.urlMaker']);
    }
}
