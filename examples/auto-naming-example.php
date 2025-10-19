<?php

declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Router;

echo "=== Auto-Naming Feature Example ===\n\n";

// Create router instance
$router = new Router();

// Enable automatic route naming
$router->enableAutoNaming();

echo "1. Simple routes with auto-naming:\n";
echo "-----------------------------------\n";

$router->get('/users', fn (): string => 'List users');
$router->get('/posts', fn (): string => 'List posts');
$router->get('/products', fn (): string => 'List products');

$route1 = $router->getRouteByName('users.get');
echo "Route name: {$route1->getName()} -> URI: {$route1->getUri()}\n";

$route2 = $router->getRouteByName('posts.get');
echo "Route name: {$route2->getName()} -> URI: {$route2->getUri()}\n";

$route3 = $router->getRouteByName('products.get');
echo "Route name: {$route3->getName()} -> URI: {$route3->getUri()}\n\n";

echo "2. Parameterized routes:\n";
echo "------------------------\n";

$router->get('/users/{id}', fn (): string => 'Show user');
$router->get('/users/{id}/posts', fn (): string => 'User posts');
$router->get('/users/{id}/posts/{post}', fn (): string => 'User post');

$route4 = $router->getRouteByName('users.id.get');
echo "Route name: {$route4->getName()} -> URI: {$route4->getUri()}\n";

$route5 = $router->getRouteByName('users.id.posts.get');
echo "Route name: {$route5->getName()} -> URI: {$route5->getUri()}\n";

$route6 = $router->getRouteByName('users.id.posts.post.get');
echo "Route name: {$route6->getName()} -> URI: {$route6->getUri()}\n\n";

echo "3. API versioned routes:\n";
echo "------------------------\n";

$router->get('/api/v1/users', fn (): string => 'API v1 users');
$router->get('/api/v1/users/{id}', fn (): string => 'API v1 user');
$router->get('/api/v2/users', fn (): string => 'API v2 users');

$route7 = $router->getRouteByName('api.v1.users.get');
echo "Route name: {$route7->getName()} -> URI: {$route7->getUri()}\n";

$route8 = $router->getRouteByName('api.v1.users.id.get');
echo "Route name: {$route8->getName()} -> URI: {$route8->getUri()}\n";

$route9 = $router->getRouteByName('api.v2.users.get');
echo "Route name: {$route9->getName()} -> URI: {$route9->getUri()}\n\n";

echo "4. Different HTTP methods:\n";
echo "--------------------------\n";

$router->get('/articles', fn (): string => 'List');
$router->post('/articles', fn (): string => 'Create');
$router->put('/articles/{id}', fn (): string => 'Update');
$router->patch('/articles/{id}', fn (): string => 'Patch');
$router->delete('/articles/{id}', fn (): string => 'Delete');

$route10 = $router->getRouteByName('articles.get');
echo "GET:    Route name: {$route10->getName()} -> URI: {$route10->getUri()}\n";

$route11 = $router->getRouteByName('articles.post');
echo "POST:   Route name: {$route11->getName()} -> URI: {$route11->getUri()}\n";

$route12 = $router->getRouteByName('articles.id.put');
echo "PUT:    Route name: {$route12->getName()} -> URI: {$route12->getUri()}\n";

$route13 = $router->getRouteByName('articles.id.patch');
echo "PATCH:  Route name: {$route13->getName()} -> URI: {$route13->getUri()}\n";

$route14 = $router->getRouteByName('articles.id.delete');
echo "DELETE: Route name: {$route14->getName()} -> URI: {$route14->getUri()}\n\n";

echo "5. Group with prefix:\n";
echo "---------------------\n";

$router->group(['prefix' => 'admin/dashboard'], function (Router $r): void{
    $r->get('/stats', fn (): string => 'Stats');
    $r->get('/users', fn (): string => 'Users');
    $r->get('/settings', fn (): string => 'Settings');
});

$route15 = $router->getRouteByName('admin.dashboard.stats.get');
echo "Route name: {$route15->getName()} -> URI: {$route15->getUri()}\n";

$route16 = $router->getRouteByName('admin.dashboard.users.get');
echo "Route name: {$route16->getName()} -> URI: {$route16->getUri()}\n";

$route17 = $router->getRouteByName('admin.dashboard.settings.get');
echo "Route name: {$route17->getName()} -> URI: {$route17->getUri()}\n\n";

echo "6. Mixed: auto-named and manually named routes:\n";
echo "-----------------------------------------------\n";

$router->get('/auto', fn (): string => 'Auto named');
$router->get('/manual', fn (): string => 'Manual named')->name('my.custom.name');

$route18 = $router->getRouteByName('auto.get');
echo "Auto-named:   {$route18->getName()} -> {$route18->getUri()}\n";

$route19 = $router->getRouteByName('my.custom.name');
echo "Manual-named: {$route19->getName()} -> {$route19->getUri()}\n\n";

echo "7. Toggle auto-naming:\n";
echo "----------------------\n";

$router->disableAutoNaming();
$router->get('/no-auto-name', fn (): string => 'Not named');

echo "Auto-naming enabled:  " . ($router->isAutoNamingEnabled() ? 'Yes' : 'No') . "\n";

$routes = $router->getRoutes();
$lastRoute = end($routes);
echo "Last route name: " . ($lastRoute->getName() ?? 'null') . " (auto-naming was disabled)\n\n";

echo "8. Complex patterns with constraints:\n";
echo "-------------------------------------\n";

$router->enableAutoNaming();
$router->get('/users/{id:\d+}/profile', fn (): string => 'Profile');
$router->get('/posts/{slug:[a-z-]+}', fn (): string => 'Post by slug');

$route20 = $router->getRouteByName('users.id.profile.get');
echo "Route name: {$route20->getName()} -> URI: {$route20->getUri()}\n";

$route21 = $router->getRouteByName('posts.slug.get');
echo "Route name: {$route21->getName()} -> URI: {$route21->getUri()}\n\n";

echo "9. Root route:\n";
echo "--------------\n";

$router->get('/', fn (): string => 'Home');
$route22 = $router->getRouteByName('root.get');
echo "Route name: {$route22->getName()} -> URI: {$route22->getUri()}\n\n";

echo "10. Route with special characters (normalized):\n";
echo "-----------------------------------------------\n";

$router->get('/api-v1/user_profile', fn (): string => 'Profile');
$route23 = $router->getRouteByName('api.v1.user.profile.get');
echo "Route name: {$route23->getName()} -> URI: {$route23->getUri()}\n";
echo "Note: Dashes (-) and underscores (_) are converted to dots (.)\n\n";

echo "=== Summary ===\n";
echo "Total routes registered: " . count($router->getRoutes()) . "\n";
echo "Auto-naming feature makes it easy to reference routes without manually naming each one!\n";
echo "You can still use custom names when needed - auto-naming won't override them.\n";

