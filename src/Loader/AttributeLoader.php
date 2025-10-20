<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Loader;

use Attribute;
use CloudCastle\Http\Router\Router;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

/**
 * Route attribute for controllers.
 *
 * @phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * @param array<string>|string $methods
     * @param array<string>|string|null $middleware
     */
    public function __construct(
        public readonly string $path,
        public readonly array|string $methods = 'GET',
        public readonly ?string $name = null,
        public readonly array|string|null $middleware = null,
        public readonly ?string $domain = null,
        public readonly ?int $throttle = null
    ) {
    }
}


/**
 * Load routes from PHP Attributes.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AttributeLoader
{
    public function __construct(private readonly Router $router)
    {
    }

    /**
     * Load routes from controller class.
     *
     * @param class-string $controllerClass
     *Р
     * @throws RuntimeException
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function loadFromController(string $controllerClass): void
    {
        if (!class_exists($controllerClass)) {
            throw new RuntimeException('Controller class not found: ' . $controllerClass);
        }

        $reflection = new ReflectionClass($controllerClass);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Route::class);

            foreach ($attributes as $attribute) {
                /** @var Route $routeAttr */
                $routeAttr = $attribute->newInstance();

                $methods = is_string($routeAttr->methods) ? [$routeAttr->methods] : $routeAttr->methods;
                $action = [$controllerClass, $method->getName()];

                $route = $this->router->match($methods, $routeAttr->path, $action);

                if ($routeAttr->name !== null) {
                    $route->name($routeAttr->name);
                }

                if ($routeAttr->middleware !== null) {
                    $middleware = is_string($routeAttr->middleware) ? [$routeAttr->middleware] : $routeAttr->middleware;
                    /** @var array<string> $middleware */
                    $route->middleware($middleware);
                }

                if ($routeAttr->domain !== null) {
                    $route->domain($routeAttr->domain);
                }

                if ($routeAttr->throttle !== null) {
                    $route->perMinute($routeAttr->throttle);
                }
            }
        }
    }

    /**
     * Load routes from multiple controllers.
     *
     * @param array<class-string> $controllers
     */
    public function loadFromControllers(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $this->loadFromController($controller);
        }
    }

    /**
     * Scan directory for controllers and load routes.
     */
    public function loadFromDirectory(string $directory, string $namespace = ''): void
    {
        if (!is_dir($directory)) {
            throw new RuntimeException('Directory not found: ' . $directory);
        }

        $files = glob($directory . '/*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            $className = $namespace . '\\' . basename($file, '.php');

            if (class_exists($className)) {
                $this->loadFromController($className);
            }
        }
    }
}
