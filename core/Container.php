<?php

namespace Core;

/**
 * Class Container
 *
 * A simple Dependency Injection (DI) container for managing service bindings and resolving instances.
 * This container allows binding class names or identifiers to factory functions (resolvers),
 * which are invoked to produce instances when requested.
 */
class Container
{
    /**
     * @var array<string, callable> Holds the bindings of service names to their resolver functions.
     */
    protected static array $bindings = [];

    /**
     * Bind a service name to a resolver callback.
     *
     * @param string $name The identifier or class name of the service.
     * @param callable $resolver A callable that returns an instance of the service.
     *
     * Example:
     * Container::bind('mailer', function() {
     *     return new Mailer();
     * });
     */
    public static function bind(string $name, callable $resolver): void
    {
        self::$bindings[$name] = $resolver;
    }

    /**
     * Resolve and retrieve an instance of the service by its name.
     *
     * @param string $name The identifier or class name of the service to resolve.
     * @return mixed The instance returned by the bound resolver.
     *
     * @throws \Exception If no binding is found for the given service name.
     *
     * Example:
     * $mailer = Container::get('mailer');
     */
    public static function get(string $name)
    {
        if (isset(self::$bindings[$name])) {
            // Call the resolver and return the service instance
            return call_user_func(self::$bindings[$name]);
        }

        // Service not found in container bindings, throw an exception
        throw new \Exception("Service {$name} not bound in container");
    }
}
