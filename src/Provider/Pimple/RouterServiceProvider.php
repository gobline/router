<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router\Provider\Pimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mendo\Router\UrlMaker;
use Mendo\Router\RequestMatcher;
use Mendo\Router\RouterCollection;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouterServiceProvider implements ServiceProviderInterface
{
    private $reference;

    public function __construct($reference = 'router')
    {
        $this->reference = $reference;
    }

    public function register(Container $container)
    {
        $container[$this->reference.'.collection'] = function ($c) {
            return new RouterCollection();
        };

        $container[$this->reference.'.requestMatcher'] = function ($c) {
            return new RequestMatcher($c[$this->reference.'.collection']);
        };

        $container[$this->reference.'.urlMaker.context'] = 'request';

        $container[$this->reference.'.urlMaker'] = function ($c) {
            $urlMaker = new UrlMaker($c[$this->reference.'.collection']);
            if (
                !empty($c[$this->reference.'.urlMaker.context']) &&
                !empty($c[$c[$this->reference.'.urlMaker.context']])
            ) {
                $urlMaker->setContext($c[$c[$this->reference.'.urlMaker.context']]);
            }

            return $urlMaker;
        };
    }
}
