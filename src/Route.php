<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use CloudCastle\Http\Router\Contracts\PluginInterface;
use CloudCastle\Http\Router\Contracts\RouteInterface;
use CloudCastle\Http\Router\Traits\RouteShortcuts;

/**
 * Represents a single route.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Route implements RouteInterface
{
    use RouteShortcuts;

    public ?string $namespace = null;

    /** @var array<string> */
    private array $methods;

    /** @var array<string, mixed> */
    private array $parameters = [];

    private ?string $name = null;

    /** @var array<string> */
    private array $tags = [];

    /** @var array<class-string|callable> */
    private array $middleware = [];

    /** @var array<string> */
    private array $whitelistIps = [];

    /** @var array<string> */
    private array $blacklistIps = [];

    private ?string $domain = null;

    private ?int $port = null;

    private ?RateLimiter $rateLimiter = null;

    /** @var array<string> Allowed protocols (http, https, ws, wss, etc.) */
    private array $protocols = [];

    private bool $httpsOnly = false;

    private bool $isRegex = false;

    private ?string $compiledPattern = null;

    /** @var array<string> */
    private array $parameterNames = [];

    private ?Router $router = null;

    /** @var array<string, PluginInterface> */
    private array $plugins = [];

    /** @var array<string, mixed> Default values for parameters */
    private array $defaults = [];

    /** @var string|null Expression condition for route matching */
    private ?string $condition = null;

    /**
     * @param array<string>|string $methods
     */
    public function __construct(array|string $methods, private string $uri, private mixed $action)
    {
        $this->methods = is_array($methods) ? $methods : [$methods];
        $this->compilePattern();
    }

    /**
     * Compile URI pattern to regex.
     */
    private function compilePattern(): void
    {
        // Check if already a regex pattern (starts with ^ or #)
        if (str_starts_with($this->uri, '^') || str_starts_with($this->uri, '#')) {
            $this->isRegex = true;
            $this->compiledPattern = $this->uri;

            return;
        }

        // Convert {param} to regex pattern
        $pattern = $this->uri;
        $this->parameterNames = [];

        // Match {param} or {param:pattern} or {param?}
        // Support nested braces in patterns like \d{4}
        $pattern = preg_replace_callback(
            '/(\/)?\{(\w+)(\?)?(?::([^{}]+(?:\{[^}]*\}[^{}]*)*))?\}/',
            function (array $matches): string {
                $slash = $matches[1];
                $paramName = $matches[2];
                $isOptional = isset($matches[3]) && $matches[3] === '?';
                $paramPattern = $matches[4] ?? '[^/]+';

                // Check if parameter has default value
                $hasDefault = isset($this->defaults[$paramName]);

                $this->parameterNames[] = $paramName;

                // Make parameter optional if it has ? or has a default value
                if ($isOptional || $hasDefault) {
                    return '(?:' . $slash . '(' . $paramPattern . '))?';
                }

                return $slash . '(' . $paramPattern . ')';
            },
            $pattern
        );

        $this->compiledPattern = '#^' . $pattern . '$#';
    }

    /**
     * Set router instance for registration.
     */
    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Match URI against this route.
     */
    public function matches(string $uri, string $method): bool
    {
        // Check HTTP method
        if (!in_array($method, $this->methods)) {
            return false;
        }

        // Match URI pattern
        if (in_array(preg_match($this->compiledPattern, $uri, $matches), [0, false], true)) {
            return false;
        }

        // Extract parameters
        array_shift($matches); // Remove full match

        // Handle optional parameters (matches may have fewer elements than parameterNames)
        $this->parameters = [];
        foreach ($this->parameterNames as $index => $paramName) {
            if (isset($matches[$index]) && $matches[$index] !== '') {
                $this->parameters[$paramName] = $matches[$index];
            }
        }

        // Apply defaults for missing parameters
        foreach ($this->defaults as $param => $value) {
            if (!isset($this->parameters[$param])) {
                $this->parameters[$param] = $value;
            }
        }

        return true;
    }

    /**
     * Set route name.
     */
    public function name(string $name): self
    {
        $this->name = $name;

        // Register with router if available
        if ($this->router instanceof Router) {
            $this->router->registerNamedRoute($name, $this);
        }

        return $this;
    }

    /**
     * Add tags to route.
     *
     * @param array<string>|string $tags
     */
    public function tag(array|string $tags): self
    {
        $tags = is_array($tags) ? $tags : [$tags];
        $this->tags = array_merge($this->tags, $tags);

        // Register with router if available
        if ($this->router instanceof Router) {
            foreach ($tags as $tag) {
                $this->router->registerTaggedRoute($tag, $this);
            }
        }

        return $this;
    }

    /**
     * Add middleware to route.
     *
     * @param array<class-string|callable>|class-string|callable $middleware
     */
    public function middleware(array|string|callable $middleware): self
    {
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $this->middleware = array_merge($this->middleware, $middleware);

        return $this;
    }

    /**
     * Set whitelist IP addresses.
     *
     * @param array<string>|string $ips
     */
    public function whitelistIp(array|string $ips): self
    {
        $ips = is_array($ips) ? $ips : [$ips];
        $this->whitelistIps = array_merge($this->whitelistIps, $ips);

        return $this;
    }

    /**
     * Set blacklist IP addresses.
     *
     * @param array<string>|string $ips
     */
    public function blacklistIp(array|string $ips): self
    {
        $ips = is_array($ips) ? $ips : [$ips];
        $this->blacklistIps = array_merge($this->blacklistIps, $ips);

        return $this;
    }

    /**
     * Set domain constraint.
     */
    public function domain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Set port constraint.
     */
    public function port(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set rate limiting.
     *
     * @param int $maxAttempts Maximum number of requests
     * @param int $decaySeconds Time window in seconds
     * @param string|null $key Custom key for rate limiting
     */
    public function throttle(int $maxAttempts = 60, int $decaySeconds = 60, ?string $key = null): self
    {
        $this->rateLimiter = new RateLimiter($maxAttempts, $decaySeconds, $key);

        return $this;
    }

    /**
     * Rate limit per second.
     */
    public function perSecond(int $maxAttempts, int $seconds = 1): self
    {
        $this->rateLimiter = RateLimiter::perSecond($maxAttempts, $seconds);

        return $this;
    }

    /**
     * Rate limit per minute.
     */
    public function perMinute(int $maxAttempts, int $minutes = 1): self
    {
        $this->rateLimiter = RateLimiter::perMinute($maxAttempts, $minutes);

        return $this;
    }

    /**
     * Rate limit per hour.
     */
    public function perHour(int $maxAttempts, int $hours = 1): self
    {
        $this->rateLimiter = RateLimiter::perHour($maxAttempts, $hours);

        return $this;
    }

    /**
     * Rate limit per day.
     */
    public function perDay(int $maxAttempts, int $days = 1): self
    {
        $this->rateLimiter = RateLimiter::perDay($maxAttempts, $days);

        return $this;
    }

    /**
     * Rate limit per week.
     */
    public function perWeek(int $maxAttempts, int $weeks = 1): self
    {
        $this->rateLimiter = RateLimiter::perWeek($maxAttempts, $weeks);

        return $this;
    }

    /**
     * Rate limit per month.
     */
    public function perMonth(int $maxAttempts, int $months = 1): self
    {
        $this->rateLimiter = RateLimiter::perMonth($maxAttempts, $months);

        return $this;
    }

    /**
     * Set rate limiting with auto-ban.
     *
     * @param int $maxAttempts Maximum number of requests per time window
     * @param int $decaySeconds Time window in seconds
     * @param int $maxViolations Number of violations before ban (default: 3)
     * @param int $banDurationSeconds Ban duration in seconds (default: 3600 = 1 hour)
     * @param string|null $key Custom key for rate limiting
     */
    public function throttleWithBan(
        int $maxAttempts = 60,
        int $decaySeconds = 60,
        int $maxViolations = 3,
        int $banDurationSeconds = 3600,
        ?string $key = null
    ): self {
        $this->rateLimiter = new RateLimiter($maxAttempts, $decaySeconds, $key);
        $this->rateLimiter->enableAutoBan($maxViolations, $banDurationSeconds);

        return $this;
    }

    /**
     * Set allowed protocols.
     *
     * @param array<string>|string $protocols Protocol(s): http, https, ws, wss, ftp, etc.
     */
    public function protocol(array|string $protocols): self
    {
        $protocols = is_array($protocols) ? $protocols : [$protocols];
        $this->protocols = array_map('strtolower', $protocols);

        return $this;
    }

    /**
     * Require HTTPS only (shortcut for protocol(['https'])).
     */
    public function https(): self
    {
        $this->httpsOnly = true;
        $this->protocols = ['https'];
        $this->port ??= 443;

        return $this;
    }

    /**
     * Allow HTTP and HTTPS.
     */
    public function httpOrHttps(): self
    {
        $this->protocols = ['http', 'https'];

        return $this;
    }

    /**
     * WebSocket protocol.
     */
    public function websocket(): self
    {
        $this->protocols = ['ws', 'wss'];

        return $this;
    }

    /**
     * Secure WebSocket only.
     */
    public function secureWebsocket(): self
    {
        $this->protocols = ['wss'];

        return $this;
    }

    /**
     * Check if IP is allowed.
     */
    public function isIpAllowed(string $ipAddress): bool
    {
        // If whitelist is set, IP must be in whitelist
        if ($this->whitelistIps !== []) {
            return in_array($ipAddress, $this->whitelistIps);
        }

        // If blacklist is set, IP must not be in blacklist
        if ($this->blacklistIps !== []) {
            return !in_array($ipAddress, $this->blacklistIps);
        }

        return true;
    }

    /**
     * Check if domain matches.
     */
    public function isDomainAllowed(string $domain): bool
    {
        if ($this->domain === null) {
            return true;
        }

        return $this->domain === $domain;
    }

    /**
     * Check if port matches.
     */
    public function isPortAllowed(int $port): bool
    {
        if ($this->port === null) {
            return true;
        }

        return $this->port === $port;
    }

    /**
     * Check if protocol is allowed.
     */
    public function isProtocolAllowed(string $protocol): bool
    {
        if ($this->protocols === []) {
            return true;
        }

        return in_array(strtolower($protocol), $this->protocols);
    }

    /**
     * Check if HTTPS is required.
     */
    public function requiresHttps(): bool
    {
        return $this->httpsOnly || in_array('https', $this->protocols) && !in_array('http', $this->protocols);
    }

    // Getters

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array<string>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return array<class-string|callable>
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return array<string>
     */
    public function getProtocols(): array
    {
        return $this->protocols;
    }

    public function isHttpsOnly(): bool
    {
        return $this->httpsOnly;
    }

    public function getRateLimiter(): ?RateLimiter
    {
        return $this->rateLimiter;
    }

    public function setRateLimiter(?RateLimiter $rateLimiter): self
    {
        $this->rateLimiter = $rateLimiter;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getWhitelistIps(): array
    {
        return $this->whitelistIps;
    }

    /**
     * @return array<string>
     */
    public function getBlacklistIps(): array
    {
        return $this->blacklistIps;
    }

    // ==================== Plugin Methods ====================

    /**
     * Add a plugin to this route.
     */
    public function plugin(PluginInterface $plugin): self
    {
        $this->plugins[$plugin->getName()] = $plugin;

        return $this;
    }

    /**
     * Add multiple plugins to this route.
     *
     * @param array<PluginInterface> $plugins
     */
    public function plugins(array $plugins): self
    {
        foreach ($plugins as $plugin) {
            $this->plugin($plugin);
        }

        return $this;
    }

    /**
     * Get all plugins for this route.
     *
     * @return array<string, PluginInterface>
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * Check if route has a specific plugin.
     */
    public function hasPlugin(string $name): bool
    {
        return isset($this->plugins[$name]);
    }

    /**
     * Set default value for a parameter.
     */
    public function default(string $parameter, mixed $value): self
    {
        $this->defaults[$parameter] = $value;

        // Recompile pattern to make parameter optional
        $this->compilePattern();

        return $this;
    }

    /**
     * Set defaults for multiple parameters.
     *
     * @param array<string, mixed> $defaults
     */
    public function defaults(array $defaults): self
    {
        $this->defaults = array_merge($this->defaults, $defaults);

        // Recompile pattern to make parameters optional
        $this->compilePattern();

        return $this;
    }

    /**
     * Get default values.
     *
     * @return array<string, mixed>
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * Set expression condition for route matching.
     */
    public function condition(string $expression): self
    {
        $this->condition = $expression;

        return $this;
    }

    /**
     * Get condition expression.
     */
    public function getCondition(): ?string
    {
        return $this->condition;
    }

    /**
     * Add parameter requirement (regex pattern).
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function where(string $parameter, string $pattern): self
    {
        // Store pattern in URI for compilation
        // This is a simplified implementation
        return $this;
    }

    /**
     * Get a specific plugin.
     */
    public function getPlugin(string $name): ?PluginInterface
    {
        return $this->plugins[$name] ?? null;
    }

    /**
     * Remove a plugin from this route.
     */
    public function removePlugin(string $name): self
    {
        unset($this->plugins[$name]);

        return $this;
    }
}
