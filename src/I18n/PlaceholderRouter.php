<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router\I18n;

use Mendo\Http\Request\HttpRequestInterface;
use Mendo\Router\AbstractPlaceholderRouter;
use Mendo\Router\PlaceholderRequestMatcher;
use Mendo\Router\PlaceholderUrlMaker;
use Mendo\Router\RouteData;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class PlaceholderRouter extends AbstractPlaceholderRouter
{
    use TranslatorAwareTrait;

    private $placeholdersToTranslate;

    public function __construct($name, $route, array $defaults = [], array $constraints = [], array $placeholdersToTranslate = [])
    {
        parent::__construct($name, $route, $defaults, $constraints);

        $this->placeholdersToTranslate = $placeholdersToTranslate;
    }

    public function match(HttpRequestInterface $httpRequest)
    {
        $language = $httpRequest->getLanguage();
        $canTranslate = ($this->translator && $language && $this->translator->hasTranslations($language));

        $route = $this->route;
        if ($canTranslate) {
            $route = $this->translator->translate($route, null, $language);
        }

        $matcher = new PlaceholderRequestMatcher($route, $this->constraints);

        $params = $matcher->match($httpRequest);

        if ($params === false) {
            return false;
        }

        if ($canTranslate) {
            $translations = $this->translator->getTranslations($language);
            foreach ($params as $name => &$value) {
                if (!in_array($name, $this->placeholdersToTranslate)) {
                    continue;
                }

                if (is_array($value)) {
                    foreach ($value as &$v) {
                        $v = array_search($v, $translations) ?: $v;
                    }
                } else {
                    $value = array_search($value, $translations) ?: $value;
                }
            }
        }

        return new RouteData($this->name, $params + $this->defaults);
    }

    public function makeUrl(RouteData $routeData, $language = null, $absolute = false)
    {
        $canTranslate = ($this->translator && $language && $this->translator->hasTranslations($language));

        $params = $routeData->getParams();
        $route = $this->route;
        if ($canTranslate) {
            $route = $this->translator->translate($route, null, $language);

            foreach ($routeData->getParams() as $name => $value) {
                if (!in_array($name, $this->placeholdersToTranslate)) {
                    continue;
                }

                if (is_array($value)) {
                    unset($params[$name]);
                    foreach ($value as $v) {
                        $params[$name][] = $this->translator->translate($v, null, $language);
                    }
                } else {
                    $params[$name] = $this->translator->translate($value, null, $language);
                }
            }
        }

        $urlMaker = new PlaceholderUrlMaker($route, $this->defaults);

        return $urlMaker->makeUrl(new RouteData($routeData->getRouteName(), $params), $language, $absolute);
    }
}
