<?php

namespace Core;

use Core\Request;

/**
 * Router class manages registration and dispatching of HTTP routes.
 *
 * Supports HTTP methods (GET, POST, PUT, DELETE), named routes,
 * middleware execution, route groups, and parameterized URIs.
 */
class Router
{
    /**
     * Registered routes array.
     *
     * Each route contains:
     * - method: HTTP method (GET, POST, etc.)
     * - uri: route URI pattern
     * - action: callback or controller@method string
     * - middleware: array of middleware classes or definitions
     * - name: optional route name for URL generation
     *
     * @var array
     */
    protected static array $routes = [];

    /**
     * Routes keyed by name for quick URL generation.
     *
     * @var array
     */
    protected static array $named = [];

    /**
     * The currently matched route after dispatch.
     *
     * @var array|null
     */
    protected static ?array $current = null;

    /**
     * Register a new route.
     *
     * Normalizes URI to always start with '/'.
     * Stores route metadata and tracks named routes.
     *
     * @param string $method HTTP method
     * @param string $uri URI pattern
     * @param callable|string|array $action Route action (callable or controller string)
     * @param array $options Optional route options (middleware, name)
     * @return void
     */
    public static function add(string $method, string $uri, $action, array $options = []): void
    {
        $uri = '/' . trim($uri, '/');
        if ($uri === '/.') {
            $uri = '/';
        }

        $route = [
            'method'     => strtoupper($method),
            'uri'        => $uri === '' ? '/' : $uri,
            'action'     => $action,
            'middleware' => $options['middleware'] ?? [],
            'name'       => $options['name'] ?? null,
        ];

        self::$routes[] = $route;

        if ($route['name']) {
            self::$named[$route['name']] = $route;
        }
    }

    /**
     * Register a GET route.
     */
    public static function get(string $uri, $action, array $options = []): void
    {
        self::add('GET', $uri, $action, $options);
    }

    /**
     * Register a POST route.
     */
    public static function post(string $uri, $action, array $options = []): void
    {
        self::add('POST', $uri, $action, $options);
    }

    /**
     * Register a PUT route.
     */
    public static function put(string $uri, $action, array $options = []): void
    {
        self::add('PUT', $uri, $action, $options);
    }

    /**
     * Register a DELETE route.
     */
    public static function delete(string $uri, $action, array $options = []): void
    {
        self::add('DELETE', $uri, $action, $options);
    }

    /**
     * Register a route for all main HTTP methods.
     */
    public static function any(string $uri, $action, array $options = []): void
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            self::add($method, $uri, $action, $options);
        }
    }

    /**
     * Dispatch the request to the matching route.
     *
     * Executes all middlewares sequentially before running the route action.
     *
     * @param Request $request Incoming HTTP request object
     * @throws \Exception If route or action is not found or middleware invalid
     * @return void
     */
    public static function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = rtrim($request->uri(), '/') ?: '/';

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = self::toRegex($route['uri']);

            if (preg_match($pattern, $uri, $matches)) {
                // Extract named parameters from regex match
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                // Execute middleware chain before the route action
                foreach ($route['middleware'] as $mw) {
                    $mwClass = $mw;
                    $mwArgs = [];

                    // Support middleware with parameters: [MiddlewareClass::class, 'arg1', 'arg2']
                    if (is_array($mw)) {
                        $mwClass = $mw[0];
                        $mwArgs = array_slice($mw, 1);
                    }

                    $mwInstance = new $mwClass(...$mwArgs);

                    if (!($mwInstance instanceof \Core\Middleware)) {
                        throw new \Exception("Middleware {$mwClass} must extend Core\\Middleware");
                    }

                    $mwInstance->handle($request);
                }

                self::$current = $route;

                self::execute($route['action'], $request, $params);
                return;
            }
        }

        // No route matched; throw 404
        throw new \Exception('Route not found', 404);
    }

    /**
     * Execute the given route action.
     *
     * Supports:
     * - Callable closures/functions
     * - Controller strings in format Controller@method
     * - Array of [ControllerClass, method]
     *
     * @param callable|string|array $action
     * @param Request $request
     * @param array $params Route parameters
     * @throws \Exception If controller or method does not exist or invalid action type
     * @return void
     */
    protected static function execute($action, Request $request, array $params = []): void
    {
        if (is_callable($action)) {
            // Call closure or callable with Request and route params
            call_user_func_array($action, array_merge([$request], array_values($params)));
            return;
        }

        if (is_string($action)) {
            if (!str_contains($action, '@')) {
                throw new \Exception("Invalid controller action format: {$action}");
            }

            [$controllerName, $method] = explode('@', $action, 2);
            $fqcn = "App\\Controllers\\{$controllerName}";

            if (!class_exists($fqcn)) {
                throw new \Exception("Controller {$fqcn} not found", 500);
            }

            $controller = new $fqcn();

            if (!method_exists($controller, $method)) {
                throw new \Exception("Method {$method} not found in controller {$fqcn}", 500);
            }

            call_user_func_array([$controller, $method], array_values($params));
            return;
        }

        if (is_array($action)) {
            if (is_string($action[0]) && class_exists($action[0])) {
                $controller = new $action[0];
                if (!method_exists($controller, $action[1])) {
                    throw new \Exception("Method {$action[1]} not found in controller {$action[0]}", 500);
                }
                call_user_func_array([$controller, $action[1]], array_values($params));
                return;
            }
        }

        throw new \Exception('Invalid route action', 500);
    }

    /**
     * Convert a URI pattern with parameters into a regex.
     *
     * Example: /user/{id} => #^/user/(?P<id>[^/]+)$#
     *
     * @param string $uri
     * @return string Regex pattern for route matching
     */
    protected static function toRegex(string $uri): string
    {
        return '#^' . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $uri) . '$#';
    }

    /**
     * Generate a URL for a named route with parameters.
     *
     * @param string $name Route name
     * @param array $params Parameters to substitute into URI
     * @throws \Exception If route name is not found
     * @return string URL string
     */
    public static function route(string $name, array $params = []): string
    {
        if (!isset(self::$named[$name])) {
            throw new \Exception("Named route [{$name}] not found");
        }

        $uri = self::$named[$name]['uri'];

        // Replace parameters in URI pattern with actual values
        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', (string)$value, $uri);
        }

        return $uri;
    }

    /**
     * Get the currently dispatched route data.
     *
     * @return array|null
     */
    public static function current(): ?array
    {
        return self::$current;
    }

    /**
     * Register a route group with shared options (e.g., middleware).
     *
     * Routes registered inside the callback will inherit the group options.
     *
     * @param array $options Group options (middleware, prefix, etc.)
     * @param callable $callback Callback that registers routes
     * @return void
     */
    public static function group(array $options, callable $callback): void
    {
        $middleware = $options['middleware'] ?? [];

        $beforeCount = count(self::$routes);

        // Execute callback to add routes
        $callback();

        $afterCount = count(self::$routes);

        // Add group middleware to all newly registered routes
        for ($i = $beforeCount; $i < $afterCount; $i++) {
            self::$routes[$i]['middleware'] = array_merge(
                self::$routes[$i]['middleware'] ?? [],
                $middleware
            );
        }
    }
}