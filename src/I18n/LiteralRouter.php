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
use Mendo\Router\AbstractLiteralRouter;
use Mendo\Router\RouteData;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class LiteralRouter extends AbstractLiteralRouter
{
    use TranslatorAwareTrait;

    public function match(HttpRequestInterface $httpRequest)
    {
        $language = $httpRequest->getLanguage();
        $canTranslate = ($this->translator && $language && $this->translator->hasTranslations($language));

        $route = $this->route;
        if ($canTranslate) {
            $route = $this->translator->translate($route, null, $language);
        }

        if ($httpRequest->getPath() !== $route) {
            return false;
        }

        return new RouteData($this->name, $this->params);
    }

    public function makeUrl(RouteData $routeData, $language = null)
    {
        $canTranslate = ($this->translator && $language && $this->translator->hasTranslations($language));

        $route = $this->route;
        if ($canTranslate) {
            $route = $this->translator->translate($route, null, $language);
        }

        return $route;
    }
}
