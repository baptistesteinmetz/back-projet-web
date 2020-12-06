<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Product;

// entitymanager doesn't seem to be working with require here ....??


class ProductController {

    public function getAll(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $products = $entityManager->getRepository('Product')->findAll();
        // $products = $entityManager->createQuery("select p from App\Models\Product")->getResult();

        $response->getBody()->write(json_encode([
            "success" => true,
            "data" => $products
        ]));
        var_dump($products[0]);
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }

}