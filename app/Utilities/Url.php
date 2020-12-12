<?php

namespace App\Utilities;

use Exception;

/**
 * Class Url
 * @package App\Utilities
 */
class Url
{
    /**
     * Get base url of current request.
     * @return string
     */
    public static function base()
    {
        static $baseUrl;

        if ($baseUrl === null) {
            if (!app()->runningInConsole()) {
                $baseUrl = request()->getSchemeAndHttpHost();
            }
        }

        return $baseUrl;
    }

    /**
     * Get full domain (without scheme)
     * @return string
     */
    public static function fullDomain()
    {
        return strtr(static::base(), ['https://' => '', 'http://' => '']);
    }

    /**
     * Match an url with a route name
     *
     * @param string $url
     * @param string $routeName
     *
     * @return bool
     */
    public static function matchRoute($url, $routeName)
    {
        try {
            return app('router')->getRoutes()->match(app('request')->create($url))->getName() === $routeName;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Match url with a route name and return string url
     *
     * @param string $url
     * @param string $routeName
     *
     * @return string
     */
    public static function matchRouteChoice($url, $routeName)
    {
        return static::matchRoute($url, $routeName) ? $url : route($routeName);
    }
}
