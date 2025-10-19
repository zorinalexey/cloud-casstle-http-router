<?php

declare(strict_types=1);

use CloudCastle\Http\Router\Loader\AttributeLoader;
use CloudCastle\Http\Router\Loader\Route;
use CloudCastle\Http\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Example controller with route attributes.
 */
class UserController
{
    #[Route('/users', methods: 'GET', name: 'users.index')]
    public function index(): array
    {
        return ['users' => ['User 1', 'User 2']];
    }

    #[Route('/users/{id}', methods: 'GET', name: 'users.show')]
    #[Route('/user/{id}', methods: 'GET')]
    public function show(int $id): array
    {
        return ['id' => $id, 'name' => "User {$id}"];
    }

    #[Route('/users', methods: 'POST', name: 'users.create', middleware: 'auth')]
    public function create(): array
    {
        return ['message' => 'User created'];
    }

    #[Route(
        '/admin/users/{id}',
        methods: ['GET', 'PUT'],
        name: 'admin.users.edit',
        middleware: ['auth', 'admin'],
        domain: 'admin.example.com',
        throttle: 30
    )]
    public function adminEdit(int $id): array
    {
        return ['admin' => true, 'id' => $id];
    }
}

// Create router
$router = new Router();

// Load routes from attributes
$loader = new AttributeLoader($router);
$loader->loadFromController(UserController::class);

// Display loaded routes
echo "Loaded routes from attributes:\n";
foreach ($router->getAllRoutes() as $route) {
    echo sprintf(
        "- %s %s => %s\n",
        implode('|', $route->getMethods()),
        $route->getUri(),
        $route->getName() ?? 'unnamed'
    );
}

// Test dispatch
echo "\nDispatching /users:\n";
$result = $router->dispatch('/users', 'GET');
print_r($result);

echo "\nDispatching /users/123:\n";
$result = $router->dispatch('/users/123', 'GET');
print_r($result);

