
<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Middlewares\CorsMiddleware;

return function(App $app) {

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->add(CorsMiddleware::class);

    $app->get('/', HomeController::class . ":home");

    $app->group('/user', function(Group $group){
        // definition des routes
        $group->post("/login", "App\Controllers\UserController:login");
        $group->post("/register", "App\Controllers\UserController:register");
        $group->post("/update", "App\Controllers\UserController:update");
        $group->get("/get/{id}", "App\Controllers\UserController:getUser");
        
    });
    $app->group('/product', function(Group $group){
        // definition des routes
        $group->get("/all", "App\Controllers\ProductController:getAll");
        $group->get("/{id}", "App\Controllers\ProductController:getOne");
        
    });

};