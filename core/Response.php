<?php

namespace Core;

/**
 * Represents an HTTP request, encapsulating method, URI,
 * query parameters, POST data, files, and headers.
 */
class Request
{
    /**
     * HTTP request method (GET, POST, PUT, DELETE, etc.).
     * @var string
     */
    protected string $method;

    /**
     * URI path of the request, excluding query parameters.
     * @var string
     */
    protected string $uri;

    /**
     * Query parameters ($_GET).
     * @var array
     */
    protected array $query;

    /**
     * Body parameters ($_POST).
     * @var array
     */
    protected array $body;

    /**
     * Uploaded files ($_FILES).
     * @var array
     */
    protected array $files;

    /**
     * HTTP request headers.
     * @var array
     */
    protected array $headers;

    /**
     * Constructor initializes request data from PHP globals.
     *
     * Supports HTTP method override via '_method' in POST data.
     *
     * @param array $server Server parameters ($_SERVER)
     * @param array $get GET parameters ($_GET)
     * @param array $post POST parameters ($_POST)
     * @param array $files Uploaded files ($_FILES)
     */
    public function __construct(array $server, array $get, array $post, array $files)
    {
        $this->method = strtoupper($server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = parse_url($server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $this->query = $get;
        $this->body = $post;
        $this->files = $files;

        // Retrieve HTTP headers if function available, else fallback to empty array.
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];

        // Support HTTP method override via _method in POST data (common for PUT, DELETE).
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
     * @return string HTTP method in uppercase
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the request URI path.
     *
     * @return string URI path without query string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Retrieve all input parameters merged from query and body.
     *
     * @return array Combined input data
     */
    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    /**
     * Retrieve a specific input parameter by key from body or query.
     *
     * Returns $default if key does not exist.
     *
     * @param string $key Input key
     * @param mixed $default Default value if key is absent
     * @return mixed
     */
    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Retrieve a specific HTTP header value by name.
     *
     * Returns $default if header is not present.
     *
     * @param string $key Header name
     * @param mixed $default Default value if header is absent
     * @return mixed
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
}