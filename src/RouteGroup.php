<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use CloudCastle\Http\Router\Contracts\PluginInterface;

/**
 * Route group for organizing routes with common attributes.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class RouteGroup
{
    private string $prefix = '';

    /** @var array<class-string|callable> */
    private array $middleware = [];

    private ?string $domain = null;

    private ?int $port = null;

    /** @var array<string> */
    private array $protocols = [];

    private bool $httpsOnly = false;

    /** @var array<string> */
    private array $whitelistIps = [];

    /** @var array<string> */
    private array $blacklistIps = [];

    private ?RateLimiter $rateLimiter = null;

    /** @var array<string> */
    private array $tags = [];

    private ?string $name = null;

    private ?string $namespace = null;

    /** @var array<Route> */
    private array $routes = [];

    /** @var array<PluginInterface> */
    private array $plugins = [];

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(private array $attributes = [])
    {
        if (isset($this->attributes['prefix'])) {
            $this->prefix = $this->attributes['prefix'];
        }

        if (isset($this->attributes['middleware'])) {
            $middleware = $this->attributes['middleware'];
            $this->middleware = is_array($middleware) ? $middleware : [$middleware];
        }

        if (isset($this->attributes['domain'])) {
            $this->domain = $this->attributes['domain'];
        }

        if (isset($this->attributes['port'])) {
            $this->port = $this->attributes['port'];
        }

        if (isset($this->attributes['whitelistIp'])) {
            $ips = $this->attributes['whitelistIp'];
            $this->whitelistIps = is_array($ips) ? $ips : [$ips];
        }

        if (isset($this->attributes['blacklistIp'])) {
            $ips = $this->attributes['blacklistIp'];
            $this->blacklistIps = is_array($ips) ? $ips : [$ips];
        }

        if (isset($this->attributes['throttle'])) {
            $throttle = $this->attributes['throttle'];
            if (is_array($throttle)) {
                $maxAttempts = $throttle['max'] ?? $throttle[0] ?? 60;
                $decayMinutes = $throttle['decay'] ?? $throttle[1] ?? 1;
                $key = $throttle['key'] ?? $throttle[2] ?? null;
                $this->rateLimiter = new RateLimiter($maxAttempts, $decayMinutes, $key);
            } elseif (is_int($throttle)) {
                $this->rateLimiter = new RateLimiter($throttle);
            }
        }

        if (isset($this->attributes['tags'])) {
            $tags = $this->attributes['tags'];
            $this->tags = is_array($tags) ? $tags : [$tags];
        }

        if (isset($this->attributes['name'])) {
            $this->name = $this->attributes['name'];
        }

        if (isset($this->attributes['namespace'])) {
            $this->namespace = $this->attributes['namespace'];
        }

        if (isset($this->attributes['plugins'])) {
            $plugins = $this->attributes['plugins'];
            $this->plugins = is_array($plugins) ? $plugins : [$plugins];
        }
    }

    /**
     * Set prefix for all routes in group.
     */
    public function prefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set throttle (rate limiting) for all routes in group.
     *
     * @param int $maxAttempts Maximum number of requests
     * @param int $decayMinutes Time window in minutes
     * @param string|null $key Custom key for rate limiting
     */
    public function throttle(int $maxAttempts = 60, int $decayMinutes = 1, ?string $key = null): self
    {
        $this->rateLimiter = new RateLimiter($maxAttempts, $decayMinutes, $key);

        return $this;
    }

    /**
     * Set namespace for all routes in group.
     */
    public function namespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Apply group attributes to a route.
     */
    public function applyToRoute(Route $route): void
    {
        // Apply prefix
        if ($this->prefix !== '' && $this->prefix !== '0') {
            $uri = $this->prefix . $route->getUri();
            // Create new route with prefixed URI
            $newRoute = new Route(
                $route->getMethods(),
                $uri,
                $route->getAction()
            );

            // Copy all attributes
            if ($route->getName() !== null && $route->getName() !== '' && $route->getName() !== '0') {
                $newRoute->name($route->getName());
            }

            $newRoute->tag($route->getTags());
            $newRoute->middleware($route->getMiddleware());
            $newRoute->whitelistIp($route->getWhitelistIps());
            $newRoute->blacklistIp($route->getBlacklistIps());

            if ($route->getDomain() !== null && $route->getDomain() !== '' && $route->getDomain() !== '0') {
                $newRoute->domain($route->getDomain());
            }

            // Replace original route (this is a workaround - we'll handle this differently in Router)
        }

        // Apply middleware
        if ($this->middleware !== []) {
            $route->middleware($this->middleware);
        }

        // Apply domain
        $routeDomain = $route->getDomain();
        if ($this->domain && ($routeDomain === null || $routeDomain === '' || $routeDomain === '0')) {
            $route->domain($this->domain);
        }

        // Apply port
        if ($this->port !== null && $route->getPort() === null) {
            $route->port($this->port);
        }

        // Apply protocols
        if ($this->protocols !== [] && $route->getProtocols() === []) {
            $route->protocol($this->protocols);
        }

        // Apply HTTPS requirement
        if ($this->httpsOnly && !$route->isHttpsOnly()) {
            $route->https();
        }

        // Apply rate limiting
        if ($this->rateLimiter instanceof RateLimiter && !$route->getRateLimiter() instanceof RateLimiter) {
            $route->setRateLimiter($this->rateLimiter);
        }

        // Apply tags
        if ($this->tags !== []) {
            $route->tag($this->tags);
        }

        // Apply name prefix
        if ($this->name !== null && $route->getName() === null) {
            $route->name($this->name);
        }

        // Apply namespace
        if ($this->namespace !== null) {
            $route->namespace = $this->namespace . '\\' . ($route->namespace ?? '');
        }

        // Apply IP restrictions
        if ($this->whitelistIps !== []) {
            $route->whitelistIp($this->whitelistIps);
        }

        if ($this->blacklistIps !== []) {
            $route->blacklistIp($this->blacklistIps);
        }

        // Apply plugins
        if ($this->plugins !== []) {
            $route->plugins($this->plugins);
        }
    }

    /**
     * Get name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name prefix for all routes in group.
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add tags to all routes in group.
     *
     * @param array<string>|string $tags
     */
    public function tag(array|string $tags): self
    {
        $tags = is_array($tags) ? $tags : [$tags];
        $this->tags = [...$this->tags, ...$tags];

        return $this;
    }

    /**
     * Get tags.
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Add middleware to all routes in group.
     *
     * @param array<class-string|callable>|class-string|callable $middleware
     */
    public function middleware(array|string|callable $middleware): self
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $this->middleware = [...$this->middleware, ...$middleware];

        return $this;
    }

    /**
     * Get middleware.
     *
     * @return array<class-string|callable>
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Add whitelist IPs to all routes in group.
     *
     * @param array<string>|string $ips
     */
    public function whitelistIp(array|string $ips): self
    {
        $ips = is_array($ips) ? $ips : [$ips];
        $this->whitelistIps = [...$this->whitelistIps, ...$ips];

        return $this;
    }

    /**
     * Get whitelist IPs.
     *
     * @return array<string>
     */
    public function getWhitelistIps(): array
    {
        return $this->whitelistIps;
    }

    /**
     * Add blacklist IPs to all routes in group.
     *
     * @param array<string>|string $ips
     */
    public function blacklistIp(array|string $ips): self
    {
        $ips = is_array($ips) ? $ips : [$ips];
        $this->blacklistIps = [...$this->blacklistIps, ...$ips];

        return $this;
    }

    /**
     * Get blacklist IPs.
     *
     * @return array<string>
     */
    public function getBlacklistIps(): array
    {
        return $this->blacklistIps;
    }

    /**
     * Get domain.
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Set domain for all routes in group.
     */
    public function domain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get port.
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Set port for all routes in group.
     */
    public function port(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set allowed protocols for all routes in group.
     *
     * @param array<string>|string $protocols
     */
    public function protocol(array|string $protocols): self
    {
        $protocols = is_array($protocols) ? $protocols : [$protocols];
        $this->protocols = array_map('strtolower', $protocols);

        return $this;
    }

    /**
     * Require HTTPS for all routes in group.
     */
    public function https(): self
    {
        $this->httpsOnly = true;
        $this->protocols = ['https'];
        $this->port ??= 443;

        return $this;
    }

    /**
     * Get rate limiter.
     */
    public function getRateLimiter(): ?RateLimiter
    {
        return $this->rateLimiter;
    }

    /**
     * Add route to group.
     */
    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Get all routes in group.
     *
     * @return array<Route>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get prefix.
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get attributes.
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get namespace.
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Add plugin to group (will be applied to all routes in group).
     *
     * @param PluginInterface|array<PluginInterface> $plugins
     */
    public function plugin(PluginInterface|array $plugins): self
    {
        $plugins = is_array($plugins) ? $plugins : [$plugins];
        $this->plugins = [...$this->plugins, ...$plugins];

        return $this;
    }

    /**
     * Get plugins.
     *
     * @return array<PluginInterface>
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }
}
