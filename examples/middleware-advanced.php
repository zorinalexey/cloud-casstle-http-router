<?php

declare(strict_types = 1);

use CloudCastle\Http\Router\Middleware\AuthMiddleware;
use CloudCastle\Http\Router\Middleware\CorsMiddleware;
use CloudCastle\Http\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

echo "=== Advanced Middleware Examples ===\n\n";

// Example 1: CORS Middleware
echo "1. CORS Middleware:\n";

$corsMiddleware = new CorsMiddleware(
    allowedOrigins : ['https://example.com', 'https://app.example.com'],
    allowedMethods : ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    allowedHeaders : ['Content-Type', 'Authorization', 'X-API-Key'],
    maxAge : 3600,
    allowCredentials : true
);

$router->get('/api/public', fn () => ['data' => 'public'])
    ->middleware($corsMiddleware);

echo "  ✓ CORS middleware configured for /api/public\n";
echo "    Allowed origins: https://example.com, https://app.example.com\n";
echo "    Allowed methods: GET, POST, PUT, DELETE, OPTIONS\n";
echo "    Max age: 3600 seconds\n";

// Example 2: Auth Middleware with custom authenticator
echo "\n2. Auth Middleware:\n";

$customAuth = new AuthMiddleware(
    authenticator : function (): ?array{
        // Custom authentication logic
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $apiKey = $_SERVER['HTTP_X_API_KEY'];
            
            // Validate API key (demo)
            if ($apiKey === 'secret-key-123') {
                return [
                    'id' => 1,
                    'name' => 'API User',
                    'roles' => ['api', 'user']
                ];
            }
        }
        
        return null;
    },
    allowedRoles : ['api']
);

$router->get('/api/protected', fn () => ['data' => 'protected'])
    ->middleware($customAuth);

echo "  ✓ Auth middleware with custom authenticator\n";
echo "    Required roles: api\n";

// Example 3: Role-based Auth
echo "\n3. Role-based Auth:\n";

$adminAuth = new AuthMiddleware(
    allowedRoles : ['admin', 'super-admin']
);

$router->get('/admin/dashboard', fn () => ['page' => 'admin dashboard'])
    ->middleware($adminAuth);

echo "  ✓ Admin-only middleware\n";
echo "    Required roles: admin, super-admin\n";

// Example 4: Multiple middleware layers
echo "\n4. Multiple Middleware:\n";

$router->group(['middleware' => [$corsMiddleware]], function ($router){
    $router->group(['middleware' => new AuthMiddleware(allowedRoles : ['user'])], function ($router){
        $router->get('/api/user/profile', fn () => ['profile' => 'data']);
        $router->post('/api/user/settings', fn () => ['updated' => true]);
    });
    
    $router->group(['middleware' => new AuthMiddleware(allowedRoles : ['admin'])], function ($router){
        $router->get('/api/admin/users', fn () => ['users' => []]);
        $router->delete('/api/admin/users/{id}', fn ($id) => ['deleted' => $id]);
    });
});

echo "  ✓ Nested middleware groups\n";
echo "    Layer 1: CORS (all routes)\n";
echo "    Layer 2a: User auth (user routes)\n";
echo "    Layer 2b: Admin auth (admin routes)\n";

// Example 5: Wildcard CORS for development
echo "\n5. Development CORS (allow all):\n";

$devCors = new CorsMiddleware(
    allowedOrigins : ['*'],
    allowedMethods : ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
    allowedHeaders : ['*'],
    maxAge : 86400
);

$router->get('/api/dev/test', fn () => ['env' => 'development'])
    ->middleware($devCors);

echo "  ✓ Development CORS middleware\n";
echo "    Allowed origins: * (all)\n";
echo "    Allowed headers: * (all)\n";

// Display all routes
echo "\n\n=== Registered Routes ===\n";
foreach ($router->getAllRoutes() as $route) {
    $middlewareCount = count($route->getMiddleware());
    echo sprintf(
        "  %s %-30s [%d middleware]\n",
        implode('|', $route->getMethods()),
        $route->getUri(),
        $middlewareCount
    );
}

