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
        if($products) {
            $response->getBody()->write(json_encode([
                "success" => true,
                "data" => $products
            ]));
        }
        else {
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            $response = $response->withStatus(401);
        }
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }

    public function getOne(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $id = intval($args['id']);
        $products = $entityManager->getRepository('Product')->findOneByIdProduct($id);
        if($products) {
            $response->getBody()->write(json_encode([
                "success" => true,
                $products
            ]));
        }
        else {
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            $response = $response->withStatus(401);
        }
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }

}