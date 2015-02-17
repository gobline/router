<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router;

use Mendo\Http\Request\HttpRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class UrlMaker implements UrlMakerInterface
{
    private $routers;
    private $httpRequest;

    public function __construct(RouterCollection $routers)
    {
        $this->routers = $routers;
    }

    public function setContext(HttpRequestInterface $httpRequest = null)
    {
        $this->httpRequest = $httpRequest;
    }

    public function getContext()
    {
        return $this->httpRequest;
    }

    public function makeUrl(RouteData $routeData, $language = null)
    {
        if (!$this->httpRequest) {
            return $this->routers->get($routeData->getRouteName())->makeUrl($routeData, $language);
        }

        if (!$language) {
            $language = $this->httpRequest->getLanguage();
        }

        $path = $this->routers->get($routeData->getRouteName())->makeUrl($routeData, $language);

        $httpRequest = clone $this->httpRequest;
        $httpRequest->setPath($path);

        $makeAbsoluteUrl = false;

        if ($language) {
            $makeAbsoluteUrl = ($httpRequest->getLanguage() !== $language);
            $httpRequest->setLanguage($language);
        }

        return $httpRequest->getUrl($makeAbsoluteUrl);
    }
}
