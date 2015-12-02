<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\I18n;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouteTranslations
{
    protected $translations;

    public function __construct(array $translations)
    {
        if (!$translations) {
            throw new \InvalidArgumentException('$translations cannot be empty');
        }

        $this->translations = $translations;
    }

    public function getTranslation($language)
    {
        if (empty($this->translations[$language])) {
            return null;
        }

        return $this->translations[$language];
    }

    public function translatePlaceholderValue($placeholder, $value, $language, $inversed = false)
    {
        if (empty($this->translations['placeholders'][$placeholder][$language])) {
            return null;
        }

        $translations = $this->translations['placeholders'][$placeholder][$language];

        if ($inversed) {
            $translations = array_flip($translations);
        }

        if (empty($translations[$value])) {
            return null;
        }

        return $translations[$value];
    }
}
