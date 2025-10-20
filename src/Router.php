<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use Closure;
use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;
use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;
use CloudCastle\Http\Router\Exceptions\MethodNotAllowedException;
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Exceptions\TooManyRequestsException;
use Exception;
use RuntimeException;

/**
 * Main HTTP Router class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.IfStatementAssignment)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Router
{
    /** @var self|null Singleton instance */
    private static ?self $instance = null;

    /** @var array<Route> */
    private array $routes = [];

    /** @var array<string, Route> */
    private array $namedRoutes = [];

    /** @var array<string, array<Route>> */
    private array $taggedRoutes = [];

    /** @var array<string, array<int>> URI index for faster lookup */
    private array $uriIndex = [];

    /** @var array<string, array<int>> Method index for faster lookup */
    private array $methodIndex = [];

    /** @var array<class-string|callable> */
    private array $globalMiddleware = [];

    /** @var array<string, mixed> */
    private array $groupStack = [];

    private ?RouteCache $cache = null;

    private ?RouteCompiler $compiler = null;

    private bool $cacheLoaded = false;

    private ?Route $currentRoute = null;

    private ?Route $previousRoute = null;

    private bool $autoNamingEnabled = false;

    /** @var array<string, PluginInterface> */
    private array $plugins = [];

    /**
     * Reset singleton instance.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    /**
     * Static: Add a GET route.
     */
    public static function staticGet(string $uri, mixed $action): Route
    {
        return self::getInstance()->get($uri, $action);
    }

    /**
     * Add a GET route.
     */
    public function get(string $uri, mixed $action): Route
    {
        return $this->addRoute(['GET'], $uri, $action);
    }

    // ==================== Static Facade Methods ====================

    /**
     * Add a route to the collection.
     *
     * @param array<string> $methods
     */
    private function addRoute(array $methods, string $uri, mixed $action): Route
    {
        // Apply group attributes
        $groupAttributes = $this->mergeGroupAttributes();

        // Apply prefix
        if (isset($groupAttributes['prefix'])) {
            $uri = trim((string) $groupAttributes['prefix'], '/') . '/' . trim($uri, '/');
        }

        $route = new Route($methods, $uri, $action);

        // Apply group middleware
        if (isset($groupAttributes['middleware'])) {
            $route->middleware($groupAttributes['middleware']);
        }

        // Apply group domain
        if (isset($groupAttributes['domain'])) {
            $route->domain($groupAttributes['domain']);
        }

        // Apply group port
        if (isset($groupAttributes['port'])) {
            $route->port($groupAttributes['port']);
        }

        // Apply group protocol
        if (isset($groupAttributes['protocol'])) {
            $route->protocol($groupAttributes['protocol']);
        }

        // Apply group HTTPS requirement
        if (isset($groupAttributes['https']) && $groupAttributes['https'] === true) {
            $route->https();
        }

        // Apply group rate limiting
        if (isset($groupAttributes['throttle'])) {
            $throttle = $groupAttributes['throttle'];
            if (is_array($throttle)) {
                $maxAttempts = $throttle['max'] ?? $throttle[0] ?? 60;
                $decayMinutes = $throttle['decay'] ?? $throttle[1] ?? 1;
                $key = $throttle['key'] ?? $throttle[2] ?? null;
                $route->throttle($maxAttempts, $decayMinutes, $key);
            } elseif (is_int($throttle)) {
                $route->throttle($throttle);
            }
        }

        // Apply group IP restrictions
        if (isset($groupAttributes['whitelistIp'])) {
            $route->whitelistIp($groupAttributes['whitelistIp']);
        }

        if (isset($groupAttributes['blacklistIp'])) {
            $route->blacklistIp($groupAttributes['blacklistIp']);
        }

        // Set router reference FIRST (required for tag registration)
        $route->setRouter($this);

        // Apply group namespace (for controller actions)
        if (isset($groupAttributes['namespace'])) {
            // Store namespace for later use when resolving controller
            $route->namespace = $groupAttributes['namespace'];
        }

        // Apply group tags (AFTER setRouter!)
        if (isset($groupAttributes['tags'])) {
            $tags = is_array($groupAttributes['tags']) ? $groupAttributes['tags'] : [$groupAttributes['tags']];
            foreach ($tags as $tag) {
                $route->tag($tag);
            }
        }

        // Apply group protocols
        if (isset($groupAttributes['protocols'])) {
            $route->protocol($groupAttributes['protocols']);
        }

        // Apply group plugins
        if (isset($groupAttributes['plugins'])) {
            $plugins = is_array($groupAttributes['plugins'])
                ? $groupAttributes['plugins']
                : [$groupAttributes['plugins']];
            $route->plugins($plugins);
        }

        // Apply auto-naming if enabled and route doesn't have a name
        if ($this->autoNamingEnabled && $route->getName() === null) {
            $autoName = $this->generateAutoName($uri, $methods);
            $route->name($autoName);
        }

        $routeIndex = count($this->routes);
        $this->routes[] = $route;

        // Build indexes for faster lookup
        $this->buildIndexes($route, $routeIndex);

        // Notify plugins about route registration
        foreach ($this->plugins as $plugin) {
            if ($plugin->isEnabled()) {
                $plugin->onRouteRegistered($route);
            }
        }

        return $route;
    }

    /**
     * Merge group attributes from stack.
     *
     * @return array<string, mixed>
     */
    private function mergeGroupAttributes(): array
    {
        if ($this->groupStack === []) {
            return [];
        }

        $attributes = [];

        foreach ($this->groupStack as $group) {
            // Merge prefixes
            if (isset($group['prefix'])) {
                $attributes['prefix'] = ($attributes['prefix'] ?? '') . '/' . trim((string) $group['prefix'], '/');
            }

            // Merge middleware
            if (isset($group['middleware'])) {
                $middleware = is_array($group['middleware']) ? $group['middleware'] : [$group['middleware']];
                $attributes['middleware'] = array_merge($attributes['middleware'] ?? [], $middleware);
            }

            // Domain (last one wins)
            if (isset($group['domain'])) {
                $attributes['domain'] = $group['domain'];
            }

            // Port (last one wins)
            if (isset($group['port'])) {
                $attributes['port'] = $group['port'];
            }

            // Protocol (merge)
            if (isset($group['protocol'])) {
                $protocols = is_array($group['protocol']) ? $group['protocol'] : [$group['protocol']];
                $attributes['protocol'] = array_merge($attributes['protocol'] ?? [], $protocols);
            }

            // HTTPS (last one wins)
            if (isset($group['https'])) {
                $attributes['https'] = $group['https'];
            }

            // Throttle (last one wins)
            if (isset($group['throttle'])) {
                $attributes['throttle'] = $group['throttle'];
            }

            // Merge IP restrictions
            if (isset($group['whitelistIp'])) {
                $ips = is_array($group['whitelistIp']) ? $group['whitelistIp'] : [$group['whitelistIp']];
                $attributes['whitelistIp'] = array_merge($attributes['whitelistIp'] ?? [], $ips);
            }

            if (isset($group['blacklistIp'])) {
                $ips = is_array($group['blacklistIp']) ? $group['blacklistIp'] : [$group['blacklistIp']];
                $attributes['blacklistIp'] = array_merge($attributes['blacklistIp'] ?? [], $ips);
            }

            // Namespace (concatenate with backslash)
            if (isset($group['namespace'])) {
                $namespace = trim((string) $group['namespace'], '\\');
                $attributes['namespace'] = ($attributes['namespace'] ?? '') . '\\' . $namespace;
            }

            // Merge plugins
            if (is_array($group) && array_key_exists('plugins', $group)) {
                $groupPlugins = $group['plugins'];
                if (is_array($groupPlugins) || $groupPlugins instanceof PluginInterface) {
                    $plugins = is_array($groupPlugins) ? $groupPlugins : [$groupPlugins];
                    $attributes['plugins'] = array_merge($attributes['plugins'] ?? [], $plugins);
                }
            }

            // Merge tags
            if (is_array($group) && array_key_exists('tags', $group)) {
                $groupTags = $group['tags'];
                if (is_array($groupTags) || is_string($groupTags)) {
                    $tags = is_array($groupTags) ? $groupTags : [$groupTags];
                    $attributes['tags'] = array_merge($attributes['tags'] ?? [], $tags);
                }
            }
        }

        return $attributes;
    }

    /**
     * Add global middleware.
     *
     * @param array<class-string|callable>|class-string|callable $middleware
     */
    public function middleware(array|string|callable $middleware): self
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $this->globalMiddleware = [...$this->globalMiddleware, ...$middleware];

        return $this;
    }

    /**
     * Build indexes for faster route lookup.
     */
    private function buildIndexes(Route $route, int $index): void
    {
        $uri = $route->getUri();

        // URI index (exact matches only)
        if (!str_contains($uri, '{')) {
            if (!isset($this->uriIndex[$uri])) {
                $this->uriIndex[$uri] = [];
            }

            $this->uriIndex[$uri][] = $index;
        }

        // Method index
        foreach ($route->getMethods() as $method) {
            if (!isset($this->methodIndex[$method])) {
                $this->methodIndex[$method] = [];
            }

            $this->methodIndex[$method][] = $index;
        }
    }

    /**
     * Get singleton instance.
     */
    public static function getInstance(): self
    {
        if (!self::$instance instanceof Router) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set singleton instance (useful for testing).
     */
    public static function setInstance(?self $instance): void
    {
        self::$instance = $instance;
    }

    /**
     * Static: Add a POST route.
     */
    public static function staticPost(string $uri, mixed $action): Route
    {
        return self::getInstance()->post($uri, $action);
    }

    /**
     * Add a POST route.
     */
    public function post(string $uri, mixed $action): Route
    {
        return $this->addRoute(['POST'], $uri, $action);
    }

    /**
     * Static: Add a PUT route.
     */
    public static function staticPut(string $uri, mixed $action): Route
    {
        return self::getInstance()->put($uri, $action);
    }

    /**
     * Add a PUT route.
     */
    public function put(string $uri, mixed $action): Route
    {
        return $this->addRoute(['PUT'], $uri, $action);
    }

    /**
     * Static: Add a PATCH route.
     */
    public static function staticPatch(string $uri, mixed $action): Route
    {
        return self::getInstance()->patch($uri, $action);
    }

    /**
     * Add a PATCH route.
     */
    public function patch(string $uri, mixed $action): Route
    {
        return $this->addRoute(['PATCH'], $uri, $action);
    }

    /**
     * Static: Add a DELETE route.
     */
    public static function staticDelete(string $uri, mixed $action): Route
    {
        return self::getInstance()->delete($uri, $action);
    }

    /**
     * Add a DELETE route.
     */
    public function delete(string $uri, mixed $action): Route
    {
        return $this->addRoute(['DELETE'], $uri, $action);
    }

    /**
     * Static: Add a VIEW route.
     */
    public static function staticView(string $uri, mixed $action): Route
    {
        return self::getInstance()->view($uri, $action);
    }

    /**
     * Add a VIEW route (custom method).
     */
    public function view(string $uri, mixed $action): Route
    {
        return $this->addRoute(['VIEW'], $uri, $action);
    }

    /**
     * Static: Add a route with custom HTTP method.
     */
    public static function staticCustom(string $method, string $uri, mixed $action): Route
    {
        return self::getInstance()->custom($method, $uri, $action);
    }

    /**
     * Add a route with custom HTTP method.
     *
     * @param string $method Custom HTTP method (e.g., 'PURGE', 'TRACE', 'CONNECT')
     */
    public function custom(string $method, string $uri, mixed $action): Route
    {
        return $this->addRoute([strtoupper($method)], $uri, $action);
    }

    /**
     * Static: Add a route with multiple HTTP methods.
     */
    public static function staticMatch(array $methods, string $uri, mixed $action): Route
    {
        return self::getInstance()->match($methods, $uri, $action);
    }

    /**
     * Add a route that responds to multiple HTTP methods.
     *
     * @param array<string> $methods
     */
    public function match(array $methods, string $uri, mixed $action): Route
    {
        return $this->addRoute($methods, $uri, $action);
    }

    /**
     * Static: Add a route for all HTTP methods.
     */
    public static function staticAny(string $uri, mixed $action): Route
    {
        return self::getInstance()->any($uri, $action);
    }

    // ==================== Instance Methods ====================

    /**
     * Add a route that responds to all HTTP methods.
     */
    public function any(string $uri, mixed $action): Route
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'VIEW', 'OPTIONS', 'HEAD'], $uri, $action);
    }

    /**
     * Static: Create a route group.
     */
    public static function staticGroup(array $attributes, Closure $callback): void
    {
        self::getInstance()->group($attributes, $callback);
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param array<string, mixed> $attributes
     */
    public function group(array $attributes, Closure $callback): RouteGroup
    {
        $group = new RouteGroup($attributes);
        $this->groupStack[] = $attributes;

        // Track routes created in this group
        $routeCountBefore = count($this->routes);

        call_user_func($callback, $this);

        array_pop($this->groupStack);

        // Add all routes created in the callback to the group
        $routeCountAfter = count($this->routes);
        for ($i = $routeCountBefore; $i < $routeCountAfter; $i++) {
            $group->addRoute($this->routes[$i]);
        }

        return $group;
    }

    /**
     * Static: Add global middleware.
     */
    public static function staticMiddleware(array|string|callable $middleware): self
    {
        return self::getInstance()->middleware($middleware);
    }

    /**
     * Static: Dispatch request.
     */
    public static function staticDispatch(
        string $uri,
        string $method,
        ?string $domain = null,
        ?string $clientIp = null,
        ?int $port = null,
        ?string $protocol = null
    ): Route {
        return self::getInstance()->dispatch($uri, $method, $domain, $clientIp, $port, $protocol);
    }

    /**
     * Dispatch the request.
     *
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     * @throws IpNotAllowedException
     * @throws TooManyRequestsException
     * @throws InsecureConnectionException
     */
    public function dispatch(
        string $uri,
        string $method,
        ?string $domain = null,
        ?string $clientIp = null,
        ?int $port = null,
        ?string $protocol = null
    ): Route {
        $method = strtoupper($method);
        $protocol = $protocol !== null && $protocol !== '' && $protocol !== '0' ? strtolower($protocol) : null;

        // Try optimized lookup first
        $route = $this->findRouteOptimized($uri, $method);

        if ($route instanceof Route) {
            // Validate constraints
            if ($domain && !$route->isDomainAllowed($domain)) {
                $route = null;
            } elseif ($port !== null && !$route->isPortAllowed($port)) {
                $route = null;
            } elseif ($protocol && !$route->isProtocolAllowed($protocol)) {
                $message = sprintf('Protocol %s not allowed for this route. Required: ', $protocol);

                throw new InsecureConnectionException($message . implode(', ', $route->getProtocols()));
            } elseif ($route->requiresHttps() && !$this->isHttpsRequest($protocol)) {
                throw new InsecureConnectionException('HTTPS required for this route');
            } elseif ($clientIp && !$route->isIpAllowed($clientIp)) {
                throw new IpNotAllowedException(sprintf('IP address %s is not allowed for this route', $clientIp));
            }

            // If route is still valid, process it
            if ($route instanceof Route) {
                // Check rate limiting
                if (($rateLimiter = $route->getRateLimiter()) instanceof RateLimiter) {
                    $identifier = $clientIp ?? 'default';

                    if ($rateLimiter->tooManyAttempts($identifier)) {
                        $exception = new TooManyRequestsException('Too many requests');
                        $exception->setLimit($rateLimiter->getMaxAttempts());
                        $exception->setRemaining($rateLimiter->remaining($identifier));
                        $exception->setRetryAfter($rateLimiter->availableIn($identifier));

                        throw $exception;
                    }

                    $rateLimiter->attempt($identifier);
                }

                // Update route history
                $this->previousRoute = $this->currentRoute;
                $this->currentRoute = $route;

                // Trigger beforeDispatch on global plugins
                foreach ($this->plugins as $plugin) {
                    if ($plugin->isEnabled()) {
                        $plugin->beforeDispatch($route, $uri, $method);
                    }
                }

                // Trigger beforeDispatch on route-specific plugins
                foreach ($route->getPlugins() as $plugin) {
                    if ($plugin->isEnabled()) {
                        $plugin->beforeDispatch($route, $uri, $method);
                    }
                }

                return $route;
            }
        }

        // Fallback to full search if optimized didn't work
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            // Check domain (early exit)
            if ($domain && !$route->isDomainAllowed($domain)) {
                continue;
            }

            // Check port (early exit)
            if ($port !== null && !$route->isPortAllowed($port)) {
                continue;
            }

            // Check protocol (early exit)
            if ($protocol && !$route->isProtocolAllowed($protocol)) {
                $message = sprintf('Protocol %s not allowed for this route. Required: ', $protocol);

                throw new InsecureConnectionException($message . implode(', ', $route->getProtocols()));
            }

            // Check HTTPS requirement (early exit)
            if ($route->requiresHttps() && !$this->isHttpsRequest($protocol)) {
                throw new InsecureConnectionException('HTTPS required for this route');
            }

            // Check if route matches URI
            if ($route->matches($uri, $method)) {
                // Check IP restrictions
                if ($clientIp && !$route->isIpAllowed($clientIp)) {
                    throw new IpNotAllowedException(sprintf('IP address %s is not allowed for this route', $clientIp));
                }

                // Check rate limiting
                if ($rateLimiter = $route->getRateLimiter()) {
                    $identifier = $clientIp ?? 'default';

                    if ($rateLimiter->tooManyAttempts($identifier)) {
                        $exception = new TooManyRequestsException('Too many requests');
                        $exception->setLimit($rateLimiter->getMaxAttempts());
                        $exception->setRemaining($rateLimiter->remaining($identifier));
                        $exception->setRetryAfter($rateLimiter->availableIn($identifier));

                        throw $exception;
                    }

                    // Record the attempt
                    $rateLimiter->attempt($identifier);
                }

                // Update route history
                $this->previousRoute = $this->currentRoute;
                $this->currentRoute = $route;

                // Trigger beforeDispatch on global plugins
                foreach ($this->plugins as $plugin) {
                    if ($plugin->isEnabled()) {
                        $plugin->beforeDispatch($route, $uri, $method);
                    }
                }

                // Trigger beforeDispatch on route-specific plugins
                foreach ($route->getPlugins() as $plugin) {
                    if ($plugin->isEnabled()) {
                        $plugin->beforeDispatch($route, $uri, $method);
                    }
                }

                return $route;
            }

            // Collect allowed methods for this URI (for better error reporting)
            foreach ($route->getMethods() as $allowedMethod) {
                if ($route->matches($uri, $allowedMethod)) {
                    $allowedMethods[] = $allowedMethod;
                }
            }
        }

        // If we found routes with different methods, throw MethodNotAllowedException
        if ($allowedMethods !== []) {
            throw (new MethodNotAllowedException(sprintf('Method %s not allowed', $method)))
                ->setAllowedMethods(array_unique($allowedMethods));
        }

        // No route found at all
        throw new RouteNotFoundException('Route not found for URI: ' . $uri);
    }

    /**
     * Optimized dispatch with indexes.
     */
    private function findRouteOptimized(string $uri, string $method): ?Route
    {
        // Try exact URI match first (fastest path)
        if (isset($this->uriIndex[$uri])) {
            foreach ($this->uriIndex[$uri] as $index) {
                $route = $this->routes[$index];
                if (in_array($method, $route->getMethods())) {
                    return $route;
                }
            }
        }

        // Try method index (reduce search space)
        if (isset($this->methodIndex[$method])) {
            foreach ($this->methodIndex[$method] as $index) {
                $route = $this->routes[$index];
                if ($route->matches($uri, $method)) {
                    return $route;
                }
            }
        }

        // Fallback to full search
        return null;
    }

    /**
     * Check if the request is HTTPS.
     */
    private function isHttpsRequest(?string $protocol): bool
    {
        if ($protocol === 'https') {
            return true;
        }

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }

        return !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on';
    }

    /**
     * Static: Enable cache.
     */
    public static function staticEnableCache(?string $cacheDir = null): self
    {
        return self::getInstance()->enableCache($cacheDir);
    }

    /**
     * Enable caching with optional cache directory.
     */
    public function enableCache(?string $cacheDir = null): self
    {
        $this->cache = new RouteCache($cacheDir);
        $this->compiler = new RouteCompiler();

        return $this;
    }

    /**
     * Static: Disable cache.
     */
    public static function staticDisableCache(): self
    {
        return self::getInstance()->disableCache();
    }

    /**
     * Disable caching.
     */
    public function disableCache(): self
    {
        if ($this->cache instanceof RouteCache) {
            $this->cache->setEnabled(false);
        }

        return $this;
    }

    /**
     * Static: Load from cache.
     */
    public static function staticLoadFromCache(): bool
    {
        return self::getInstance()->loadFromCache();
    }

    /**
     * Load routes from cache (automatic).
     *
     * @return bool Whether routes were loaded from cache
     */
    public function loadFromCache(): bool
    {
        if (!$this->cache instanceof RouteCache || $this->cacheLoaded) {
            return false;
        }

        $cached = $this->cache->get();

        if ($cached === null) {
            return false;
        }

        if (!$this->compiler instanceof RouteCompiler) {
            $this->compiler = new RouteCompiler();
        }

        // Restore routes from cache
        $routes = $this->compiler->restore($cached);

        if ($routes === []) {
            return false;
        }

        // Replace current routes with cached ones
        $this->routes = [];
        $this->namedRoutes = [];
        $this->taggedRoutes = [];

        foreach ($routes as $route) {
            $route->setRouter($this);
            $this->routes[] = $route;

            // Rebuild named routes index
            if ($route->getName() !== null) {
                $this->namedRoutes[$route->getName()] = $route;
            }

            // Rebuild tagged routes index
            foreach ($route->getTags() as $tag) {
                if (!isset($this->taggedRoutes[$tag])) {
                    $this->taggedRoutes[$tag] = [];
                }

                $this->taggedRoutes[$tag][] = $route;
            }
        }

        $this->cacheLoaded = true;

        return true;
    }

    /**
     * Static: Compile routes.
     */
    public static function staticCompile(bool $force = false): bool
    {
        return self::getInstance()->compile($force);
    }

    /**
     * Compile and cache routes (manual compilation).
     *
     * @param bool $force Force recompilation even if cache exists
     *
     * @return bool Success status
     */
    public function compile(bool $force = false): bool
    {
        if (!$this->cache instanceof RouteCache) {
            $this->enableCache();
        }

        if (!$this->compiler instanceof RouteCompiler) {
            $this->compiler = new RouteCompiler();
        }

        // Skip if cache exists and force is not set
        if (!$force && $this->cache->exists()) {
            return true;
        }

        // Compile routes
        $compiled = $this->compiler->compile($this->routes);

        // Save to cache
        try {
            $this->cache->put($compiled);

            return true;
        } catch (RuntimeException) {
            return false;
        }
    }

    /**
     * Static: Clear cache.
     */
    public static function staticClearCache(): bool
    {
        return self::getInstance()->clearCache();
    }

    /**
     * Clear route cache.
     */
    public function clearCache(): bool
    {
        if (!$this->cache instanceof RouteCache) {
            return false;
        }

        return $this->cache->clear();
    }

    /**
     * Static: Get route by name.
     */
    public static function staticGetRouteByName(string $name): ?Route
    {
        return self::getInstance()->getRouteByName($name);
    }

    /**
     * Find a route by name.
     */
    public function getRouteByName(string $name): ?Route
    {
        return $this->namedRoutes[$name] ?? null;
    }

    /**
     * Static: Get routes by tag.
     */
    public static function staticGetRoutesByTag(string $tag): array
    {
        return self::getInstance()->getRoutesByTag($tag);
    }

    /**
     * Get routes by tag.
     *
     * @return array<Route>
     */
    public function getRoutesByTag(string $tag): array
    {
        return $this->taggedRoutes[$tag] ?? [];
    }

    /**
     * Static: Get all routes.
     */
    public static function staticGetRoutes(): array
    {
        return self::getInstance()->getRoutes();
    }

    /**
     * Get all routes.
     *
     * @return array<Route>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Static: Auto compile.
     */
    public static function staticAutoCompile(): void
    {
        self::getInstance()->autoCompile();
    }

    /**
     * Auto-compile routes on shutdown (for auto caching).
     */
    public function autoCompile(): void
    {
        if (!$this->cache instanceof RouteCache || $this->cacheLoaded) {
            return;
        }

        // Only compile if cache doesn't exist
        if (!$this->cache->exists()) {
            $this->compile();
        }
    }

    /**
     * Get routes count.
     */
    public function count(): int
    {
        return count($this->routes);
    }

    /**
     * Get all routes as JSON.
     */
    public function getRoutesAsJson(int $flags = 0): string
    {
        return json_encode($this->getRoutesAsArray(), $flags);
    }

    /**
     * Get all routes as array with details.
     *
     * @return array<array<string, mixed>>
     */
    public function getRoutesAsArray(): array
    {
        return array_map(fn (Route $route): array => [
            'uri' => $route->getUri(),
            'methods' => $route->getMethods(),
            'name' => $route->getName(),
            'tags' => $route->getTags(),
            'middleware' => $route->getMiddleware(),
            'domain' => $route->getDomain(),
            'port' => $route->getPort(),
            'action' => $this->describeAction($route->getAction()),
            'has_throttle' => $route->getRateLimiter() instanceof RateLimiter,
            'has_ip_restriction' => $route->getWhitelistIps() !== [] || $route->getBlacklistIps() !== [],
        ], $this->routes);
    }

    /**
     * Describe action for display.
     */
    private function describeAction(mixed $action): string
    {
        if ($action instanceof Closure) {
            return 'Closure';
        }

        if (is_array($action)) {
            $controller = is_object($action[0]) ? $action[0]::class : $action[0];

            return sprintf('%s@%s', $controller, $action[1]);
        }

        if (is_string($action)) {
            return $action;
        }

        return 'Unknown';
    }

    /**
     * Get routes grouped by method.
     *
     * @return array<string, array<Route>>
     */
    public function getRoutesGroupedByMethod(): array
    {
        $grouped = [];

        foreach ($this->routes as $route) {
            foreach ($route->getMethods() as $method) {
                if (!isset($grouped[$method])) {
                    $grouped[$method] = [];
                }

                $grouped[$method][] = $route;
            }
        }

        return $grouped;
    }

    /**
     * Get routes grouped by prefix.
     *
     * @return array<string, array<Route>>
     */
    public function getRoutesGroupedByPrefix(): array
    {
        $grouped = [];

        foreach ($this->routes as $route) {
            $uri = $route->getUri();
            $segments = explode('/', trim($uri, '/'));
            $prefix = '/' . ($segments[0] ?? '');

            if (!isset($grouped[$prefix])) {
                $grouped[$prefix] = [];
            }

            $grouped[$prefix][] = $route;
        }

        return $grouped;
    }

    /**
     * Get routes grouped by domain.
     *
     * @return array<string, array<Route>>
     */
    public function getRoutesGroupedByDomain(): array
    {
        $grouped = ['default' => []];

        foreach ($this->routes as $route) {
            $domain = $route->getDomain() ?? 'default';

            if (!isset($grouped[$domain])) {
                $grouped[$domain] = [];
            }

            $grouped[$domain][] = $route;
        }

        return $grouped;
    }

    /**
     * Get all named routes.
     *
     * @return array<string, Route>
     */
    public function getNamedRoutes(): array
    {
        return $this->namedRoutes;
    }

    /**
     * Get global middleware.
     *
     * @return array<class-string|callable>
     */
    public function getGlobalMiddleware(): array
    {
        return $this->globalMiddleware;
    }

    /**
     * Register named route.
     */
    public function registerNamedRoute(string $name, Route $route): void
    {
        $this->namedRoutes[$name] = $route;
    }

    /**
     * Register tagged route.
     */
    public function registerTaggedRoute(string $tag, Route $route): void
    {
        if (!isset($this->taggedRoutes[$tag])) {
            $this->taggedRoutes[$tag] = [];
        }

        $this->taggedRoutes[$tag][] = $route;
    }

    /**
     * Get route by name.
     */
    public function getRoute(string $name): ?Route
    {
        return $this->namedRoutes[$name] ?? null;
    }

    /**
     * Get all registered routes.
     *
     * @return array<Route>
     */
    public function getAllRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get cache instance.
     */
    public function getCache(): ?RouteCache
    {
        return $this->cache;
    }

    /**
     * Set cache instance.
     */
    public function setCache(RouteCache $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    // ==================== Route Filtering Methods ====================

    /**
     * Check if routes are loaded from cache.
     */
    public function isCacheLoaded(): bool
    {
        return $this->cacheLoaded;
    }

    /**
     * Get current route (last dispatched).
     */
    public function current(): ?Route
    {
        return $this->currentRoute;
    }

    /**
     * Get current route name.
     */
    public function currentRouteName(): ?string
    {
        return $this->currentRoute?->getName();
    }

    /**
     * Check if current route has a specific name.
     */
    public function currentRouteNamed(string $name): bool
    {
        return $this->currentRoute?->getName() === $name;
    }

    /**
     * Get previous route (before last dispatch).
     */
    public function previous(): ?Route
    {
        return $this->previousRoute;
    }

    /**
     * Get previous route name.
     */
    public function previousRouteName(): ?string
    {
        return $this->previousRoute?->getName();
    }

    /**
     * Check if previous route has a specific name.
     */
    public function previousRouteNamed(string $name): bool
    {
        return $this->previousRoute?->getName() === $name;
    }

    /**
     * Get previous route URI.
     */
    public function previousRouteUri(): ?string
    {
        return $this->previousRoute?->getUri();
    }

    /**
     * Get routes by domain.
     *
     * @return array<Route>
     */
    public function getRoutesByDomain(string $domain): array
    {
        return array_filter($this->routes, fn (Route $route): bool => $route->getDomain() === $domain);
    }

    /**
     * Get routes by port.
     *
     * @return array<Route>
     */
    public function getRoutesByPort(int $port): array
    {
        return array_filter($this->routes, fn (Route $route): bool => $route->getPort() === $port);
    }

    /**
     * Get routes that allow specific IP (in whitelist).
     *
     * @return array<Route>
     */
    public function getRoutesByWhitelistedIp(string $ipAddress): array
    {
        return array_filter($this->routes, function (Route $route) use ($ipAddress): bool {
            $whitelist = $route->getWhitelistIps();

            return $whitelist !== [] && in_array($ipAddress, $whitelist);
        });
    }

    /**
     * Get routes that block specific IP (in blacklist).
     *
     * @return array<Route>
     */
    public function getRoutesByBlacklistedIp(string $ipAddress): array
    {
        return array_filter($this->routes, function (Route $route) use ($ipAddress): bool {
            $blacklist = $route->getBlacklistIps();

            return $blacklist !== [] && in_array($ipAddress, $blacklist);
        });
    }

    /**
     * Get routes that have middleware.
     *
     * @return array<Route>
     */
    public function getRoutesByMiddleware(string $middleware): array
    {
        return array_filter($this->routes, fn (Route $route): bool => in_array($middleware, $route->getMiddleware()));
    }

    /**
     * Get routes that match URI pattern.
     *
     * @return array<Route>
     */
    public function getRoutesByUriPattern(string $pattern): array
    {
        return array_filter($this->routes, fn (Route $route): bool => str_contains($route->getUri(), $pattern));
    }

    /**
     * Get routes by prefix.
     *
     * @return array<Route>
     */
    public function getRoutesByPrefix(string $prefix): array
    {
        return array_filter($this->routes, fn (Route $route): bool => str_starts_with($route->getUri(), $prefix));
    }

    /**
     * Get all named routes that match pattern.
     *
     * @return array<string, Route>
     */
    public function getNamedRoutesMatching(string $pattern): array
    {
        return array_filter(
            $this->namedRoutes,
            fn (string $name): bool => str_contains($name, $pattern),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Get routes by action type.
     *
     * @param string $type 'closure', 'array', 'string'
     *
     * @return array<Route>
     */
    public function getRoutesByActionType(string $type): array
    {
        return array_filter($this->routes, function (Route $route) use ($type): bool {
            $action = $route->getAction();

            return match ($type) {
                'closure' => $action instanceof Closure,
                'array' => is_array($action),
                'string' => is_string($action),
                default => false,
            };
        });
    }

    /**
     * Get routes by controller.
     *
     * @return array<Route>
     */
    public function getRoutesByController(string $controller): array
    {
        return array_filter($this->routes, function (Route $route) use ($controller): bool {
            $action = $route->getAction();

            if (is_array($action)) {
                $actionController = is_object($action[0]) ? $action[0]::class : $action[0];

                return $actionController === $controller || str_contains($actionController, $controller);
            }

            if (is_string($action)) {
                return str_contains($action, $controller);
            }

            return false;
        });
    }

    /**
     * Get statistics about routes.
     *
     * @return array<string, mixed>
     */
    public function getRouteStats(): array
    {
        return [
            'total' => count($this->routes),
            'named' => count($this->namedRoutes),
            'tagged' => count($this->taggedRoutes),
            'with_middleware' => count(array_filter(
                $this->routes,
                fn ($route): bool => $route->getMiddleware() !== []
            )),
            'with_domain' => count($this->getRoutesWithDomain()),
            'with_port' => count($this->getRoutesWithPort()),
            'with_ip_restrictions' => count($this->getRoutesWithIpRestrictions()),
            'throttled' => count($this->getThrottledRoutes()),
            'by_method' => [
                'GET' => count($this->getRoutesByMethod('GET')),
                'POST' => count($this->getRoutesByMethod('POST')),
                'PUT' => count($this->getRoutesByMethod('PUT')),
                'PATCH' => count($this->getRoutesByMethod('PATCH')),
                'DELETE' => count($this->getRoutesByMethod('DELETE')),
                'VIEW' => count($this->getRoutesByMethod('VIEW')),
            ],
        ];
    }

    /**
     * Get routes with domain restriction.
     *
     * @return array<Route>
     */
    public function getRoutesWithDomain(): array
    {
        return array_filter($this->routes, fn (Route $route): bool => $route->getDomain() !== null);
    }

    /**
     * Get routes with port restriction.
     *
     * @return array<Route>
     */
    public function getRoutesWithPort(): array
    {
        return array_filter($this->routes, fn (Route $route): bool => $route->getPort() !== null);
    }

    /**
     * Get routes with IP restrictions (whitelist or blacklist).
     *
     * @return array<Route>
     */
    public function getRoutesWithIpRestrictions(): array
    {
        return array_filter(
            $this->routes,
            fn (Route $route): bool => $route->getWhitelistIps() !== [] || $route->getBlacklistIps() !== []
        );
    }

    /**
     * Get routes with rate limiting.
     *
     * @return array<Route>
     */
    public function getThrottledRoutes(): array
    {
        return array_filter($this->routes, fn (Route $route): bool => $route->getRateLimiter() instanceof RateLimiter);
    }

    /**
     * Get routes by HTTP method.
     *
     * @return array<Route>
     */
    public function getRoutesByMethod(string $method): array
    {
        $method = strtoupper($method);

        return array_filter($this->routes, fn (Route $route): bool => in_array($method, $route->getMethods()));
    }

    /**
     * Search routes by multiple criteria.
     *
     * @param array<string, mixed> $criteria
     *
     * @return array<Route>
     */
    public function searchRoutes(array $criteria): array
    {
        return array_filter($this->routes, function (Route $route) use ($criteria): bool {
            foreach ($criteria as $key => $value) {
                $match = match ($key) {
                    'name' => $route->getName() === $value,
                    'tag' => in_array($value, $route->getTags()),
                    'method' => in_array($value, $route->getMethods()),
                    'domain' => $route->getDomain() === $value,
                    'port' => $route->getPort() === $value,
                    'middleware' => in_array($value, $route->getMiddleware()),
                    'prefix' => str_starts_with($route->getUri(), $value),
                    'pattern' => str_contains($route->getUri(), $value),
                    'has_throttle' => ($route->getRateLimiter() instanceof RateLimiter) === $value,
                    'has_domain' => ($route->getDomain() !== null) === $value,
                    'has_ip_restriction' => (
                        $route->getWhitelistIps() !== [] || $route->getBlacklistIps() !== []
                    ) === $value,
                    default => true,
                };

                if (!$match) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Check if route exists by name.
     */
    public function hasRoute(string $name): bool
    {
        return isset($this->namedRoutes[$name]);
    }

    /**
     * Check if any route has tag.
     */
    public function hasTag(string $tag): bool
    {
        return isset($this->taggedRoutes[$tag]);
    }

    /**
     * Get all unique tags.
     *
     * @return array<string>
     */
    public function getAllTags(): array
    {
        return array_keys($this->taggedRoutes);
    }

    /**
     * Get all unique domains.
     *
     * @return array<string>
     */
    public function getAllDomains(): array
    {
        $domains = [];
        foreach ($this->routes as $route) {
            if ($domain = $route->getDomain()) {
                $domains[] = $domain;
            }
        }

        return array_unique($domains);
    }

    /**
     * Get all unique ports.
     *
     * @return array<int>
     */
    public function getAllPorts(): array
    {
        $ports = [];
        foreach ($this->routes as $route) {
            if ($port = $route->getPort()) {
                $ports[] = $port;
            }
        }

        return array_unique($ports);
    }

    // ==================== Auto-Naming Methods ====================

    /**
     * Enable auto-naming for routes.
     */
    public function enableAutoNaming(): self
    {
        $this->autoNamingEnabled = true;

        return $this;
    }

    /**
     * Disable auto-naming for routes.
     */
    public function disableAutoNaming(): self
    {
        $this->autoNamingEnabled = false;

        return $this;
    }

    /**
     * Check if auto-naming is enabled.
     */
    public function isAutoNamingEnabled(): bool
    {
        return $this->autoNamingEnabled;
    }

    /**
     * Generate automatic name for route.
     */
    private function generateAutoName(string $uri, array $methods): string
    {
        // Remove leading/trailing slashes
        $uri = trim($uri, '/');

        // Replace {param:pattern} with param
        $result = preg_replace('/\{(\w+):[^}]+\}/', '$1', $uri);
        $uri = is_string($result) ? $result : $uri;

        // Replace {param} with param
        $result = preg_replace('/\{(\w+)\}/', '$1', $uri);
        $uri = is_string($result) ? $result : $uri;

        // Replace hyphens and underscores with dots (for special characters like api-v1 -> api.v1)
        $uri = str_replace(['-', '_'], '.', $uri);

        // Replace slashes with dots
        $uri = str_replace('/', '.', $uri);

        // Remove consecutive dots (e.g., api..v1 -> api.v1)
        $result = preg_replace('/\.+/', '.', $uri);
        $uri = is_string($result) ? $result : $uri;

        // Remove leading/trailing dots
        $uri = trim($uri, '.');

        // Handle root route
        if ($uri === '') {
            $uri = 'root';
        }

        // Append method (lowercase)
        $method = strtolower((string) $methods[0]);

        return $uri . '.' . $method;
    }

    // ==================== Plugin System Methods ====================

    /**
     * Register a plugin.
     */
    public function registerPlugin(PluginInterface $plugin): self
    {
        $this->plugins[$plugin->getName()] = $plugin;

        // Boot the plugin
        $plugin->boot($this);

        return $this;
    }

    /**
     * Unregister a plugin.
     */
    public function unregisterPlugin(string $name): self
    {
        unset($this->plugins[$name]);

        return $this;
    }

    /**
     * Get all registered plugins.
     *
     * @return array<string, PluginInterface>
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * Get a specific plugin.
     */
    public function getPlugin(string $name): ?PluginInterface
    {
        return $this->plugins[$name] ?? null;
    }

    /**
     * Check if plugin is registered.
     */
    public function hasPlugin(string $name): bool
    {
        return isset($this->plugins[$name]);
    }

    /**
     * Execute a route with plugin hooks.
     */
    public function executeRoute(Route $route): mixed
    {
        // Execute the route action
        $result = null;

        try {
            $action = $route->getAction();
            if ($action instanceof Closure) {
                $result = $action();
            } elseif (is_array($action) && is_callable($action)) {
                $result = call_user_func($action);
            } elseif (is_string($action)) {
                // Handle controller@method format
                if (str_contains($action, '@')) {
                    [$controller, $method] = explode('@', $action, 2);
                    if (class_exists($controller)) {
                        $controllerInstance = new $controller();
                        /** @var callable $callable */
                        $callable = [$controllerInstance, $method];
                        $result = call_user_func($callable);
                    }
                }
            }
        } catch (Exception $exception) {
            // Call onException on all plugins
            foreach ($this->plugins as $plugin) {
                if ($plugin->isEnabled()) {
                    $plugin->onException($exception);
                }
            }

            foreach ($route->getPlugins() as $plugin) {
                if ($plugin->isEnabled()) {
                    $plugin->onException($exception);
                }
            }

            throw $exception;
        }

        // Call afterDispatch on route-specific plugins (in reverse order)
        foreach (array_reverse($route->getPlugins()) as $plugin) {
            if ($plugin->isEnabled()) {
                $result = $plugin->afterDispatch($route, $result);
            }
        }

        // Call afterDispatch on global plugins (in reverse order)
        foreach (array_reverse($this->plugins) as $plugin) {
            if ($plugin->isEnabled()) {
                $result = $plugin->afterDispatch($route, $result);
            }
        }

        return $result;
    }
}
