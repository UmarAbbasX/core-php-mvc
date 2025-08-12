<?php

namespace Core;

/**
 * Represents an HTTP request and provides convenient methods
 * to access request data such as method, URI, inputs, headers, and files.
 */
class Request
{
    /**
     * @var string HTTP request method (GET, POST, PUT, DELETE, etc.)
     */
    protected string $method;

    /**
     * @var string Request URI path (without query string)
     */
    protected string $uri;

    /**
     * @var array Query parameters ($_GET)
     */
    protected array $query;

    /**
     * @var array Request body parameters ($_POST)
     */
    protected array $body;

    /**
     * @var array Uploaded files ($_FILES)
     */
    protected array $files;

    /**
     * @var array HTTP request headers
     */
    protected array $headers;

    /**
     * Constructor to initialize the request object from PHP superglobals.
     *
     * Supports HTTP method override via `_method` POST parameter.
     *
     * @param array $server Server variables ($_SERVER)
     * @param array $get Query parameters ($_GET)
     * @param array $post Post parameters ($_POST)
     * @param array $files Uploaded files ($_FILES)
     */
    public function __construct(array $server, array $get, array $post, array $files)
    {
        $this->method = strtoupper($server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = parse_url($server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $this->query = $get;
        $this->body = $post;
        $this->files = $files;

        // Get request headers if possible
        if (function_exists('getallheaders')) {
            $this->headers = getallheaders();
        } else {
            // Fallback for environments without getallheaders()
            $this->headers = [];
            foreach ($server as $key => $value) {
                if (str_starts_with($key, 'HTTP_')) {
                    $headerName = str_replace('_', '-', substr($key, 5));
                    $this->headers[$headerName] = $value;
                }
            }
        }

        // Handle HTTP method override (e.g., for PUT, DELETE via POST form)
        if ($this->method === 'POST' && isset($post['_method'])) {
            $this->method = strtoupper($post['_method']);
        }
    }

    /**
     * Factory method to capture the current HTTP request.
     *
     * @return static
     */
    public static function capture(): self
    {
        return new self($_SERVER, $_GET, $_POST, $_FILES);
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the URI path of the request (without query parameters).
     *
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get all input parameters from query and body merged.
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    /**
     * Get a specific input value from body or query, or return default.
     *
     * @param string $key Input key
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public function input(string $key, $default = null)
    {
        if (array_key_exists($key, $this->body)) {
            return $this->body[$key];
        }

        if (array_key_exists($key, $this->query)) {
            return $this->query[$key];
        }

        return $default;
    }

    /**
     * Get a specific HTTP request header value or default if not present.
     *
     * Header name is case sensitive based on how PHP provides headers.
     *
     * @param string $key Header name
     * @param mixed $default Default value if header not found
     * @return mixed
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get uploaded files array.
     *
     * @return array
     */
    public function files(): array
    {
        return $this->files;
    }
}