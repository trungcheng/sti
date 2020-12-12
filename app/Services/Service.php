<?php

namespace App\Services;

use BadMethodCallException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;

/**
 * Class Service
 * @package App\Services
 */
abstract class Service
{
    /**
     * Prefix for call static magic method.
     */
    protected const PREFIX_METHOD = 'do';

    /**
     * @var static Singleton for all services.
     */
    protected static $instances = [];

    /**
     * Method initialization.
     *
     * @return static
     * @throws BindingResolutionException
     */
    public static function init()
    {
        $serviceName = static::class;

        if (!isset(self::$instances[$serviceName])) {
            self::$instances[$serviceName] = app()->make(static::class);
        }

        return self::$instances[$serviceName];
    }

    /**
     * Alias of method init.
     *
     * @return static
     * @throws BindingResolutionException
     */
    public static function on()
    {
        return static::init();
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws BadMethodCallException
     * @throws BindingResolutionException
     */
    public static function __callStatic($name, $arguments)
    {
        static::init();

        $serviceName = static::class;

        $instance = self::$instances[$serviceName];

        if (Str::startsWith($name, static::PREFIX_METHOD)) {
            $method = substr($name, strlen(static::PREFIX_METHOD));

            $method = lcfirst($method);

            if (method_exists($instance, $method)) {
                return call_user_func_array([$instance, $method], $arguments);
            }
        }

        throw new BadMethodCallException('Method ' . $name . ' not exists in class ' . static::class . '.');
    }
}
