<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use User;

// entitymanager doesn't seem to be working with require here ....??


class UserController {

    public function login(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $err=false;
        $body = $request->getParsedBody();
        $login = $body ['login'] ?? "";
        $pass = $body ['password'] ?? "";
    
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login))   {
            $err = true;
        }
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$pass))  {
            $err=true;
        }
        if (!$err) {
            $userRepo = $entityManager->getRepository('User');
            $user = $userRepo->findOneBy(array('login' => $login, 'password' => $pass));
            if ($user and $login == $user->getLogin() and $pass == $user->getPassword()) {
                $data = array('nom' => $user->getFirstname(), 'prenom' => $user->getLastname());
                $issuedAt = time();
                $payload = [
                    "user" => [
                        "id" => $user->getIdUser()
                    ],
                    "iat" => $issuedAt,
                    "exp" => $issuedAt + 60,
                ];
                $token_jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], "HS256");
        
                $response->getBody()->write(json_encode([
                    "success" => true,
                    "data" => $data,
                ]));
                $response
                // ->withHeader("Authorization", $token_jwt)
                ->withHeader("Content-Type", "application/json");
                // ->withHeader('Access-Control-Expose-Headers', '*');
            } else {     
                $response->getBody()->write(json_encode([
                    "success" => false
                ]));     
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode([
                "success" => false
            ]));
            $response = $response->withStatus(401);
        }
        return $response;
    }

    public function register(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $userRepo = $entityManager->getRepository('User');
        $body = $request->getParsedBody();
        $err = false;
        foreach($body as $key => $value){
            ${$key} = $value ?? "";
        }
        // TODO : pregmatch à améliorer trop de choses passent
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$password ||$passwor == ""))  {
            $err=true;
        }
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login) ||$login == "")   {
            $err = true;
        }
        if (!preg_match("/[a-zA-Z0-9-]{1,20}/",$adress) ||$adress == "")  {
            $err=true;
        }
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$mail) ||$mail == "")   {
            $err = true;
        }
        if (!preg_match("/[a-zA-Z]/",$firstname) ||$firstname == "")  {
            $err=true;
        }
        if (!preg_match("/[a-zA-Z]/",$lastname) ||$lastname == "")   {
            $err = true;
        }

        if($err) {
            $result = [
                "success" => false,
            ];
            $response = $response->withStatus(401);
        }
        else {
            $user = new User();
            $user
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setAddress($adress)
            ->setCity($city)
            ->setZipcode($zipcode)
            ->setMail($mail)
            ->setPhone($phone)
            ->setCountry($country)
            ->setPassword($password)
            ->setLogin($login)
            ->setGender($gender)
            ;
            $result = [
                "success" => true,
                "user" => $body,
            ];
            $entityManager->persist($user);
            $entityManager->flush();
            $response->getBody()->write(json_encode($result));
            $response->withHeader("Content-Type", "application/json");
            // ->withHeader('Access-Control-Expose-Headers', '*');
        }
        return $response;
    }
}