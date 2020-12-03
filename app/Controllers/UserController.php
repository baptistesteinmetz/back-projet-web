<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT; 

class UserController {

    public function login(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $login = $data['login'] ?? "";
        $password = $data['password'] ?? "";

        if($login !== "baptiste" || $password !== "baptiste") {
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            return $response->withHeader("Content-Type", "application/json");
        }
        else {
            $issuedAt = time();
            $payload = [
                "user" => [
                    "id" => 1,
                ],
                "iat" => $issuedAt,
                "exp" => $issuedAt + 60,
            ];
            $token_jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], "HS256");
    
            $response->getBody()->write(json_encode([
                "success" => true,
            ]));
            
            return $response
            ->withHeader("Authorization", $token_jwt)
            ->withHeader("Content-Type", "application/json")
            ->withHeader('Access-Control-Expose-Headers', '*');
        }

    }

    public function register(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $result = [
            "success" => true,
            "user" => $data,
        ];
        $response->getBody()->write(json_encode($result));
        return $response->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }
}