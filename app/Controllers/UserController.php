<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use User;

// entitymanager doesn't seem to be working with require here ....??


class UserController {


    // post login generating JWT
    public function login(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $err=false;
        $body = $request->getParsedBody();
        foreach($body as $key => $value){
            ${$key} = $value ?? "";
        }
        if (!$err) {
            $userRepo = $entityManager->getRepository('User');
            $user = $userRepo->findOneBy(array('login' => $login));
            if ($user && $login == $user->getLogin() && $password == $user->getPassword()) {
                $data = array('nom' => $user->getFirstname(), 'prenom' => $user->getLastname());
                $response = $this->createJwt($response, $user);
                $response->getBody()->write(json_encode([
                    "success" => true,
                    "data" => $data,
                ]));
                $response
                ->withHeader("Content-Type", "application/json");
            } else {     
                $response->getBody()->write(json_encode([
                    "success" => false
                ]));     
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode([
                "success" => false,
                "body" => $body,
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
        // TODO : pregmatch à améliorer
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$password ||$password == ""))  {
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


    public function update(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $userRepo = $entityManager->getRepository('User');
        $body = $request->getParsedBody();
        $err = false;
        foreach($body as $key => $value){
            ${$key} = $value ?? "";
        }
        // TODO : pregmatch à améliorer
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$password ||$password == ""))  {
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
            $user = $userRepo->findOneBy(array('idUser' => $idUser));
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


    // get User by ID
    public function getUser(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $id = intval($args['id']);
        $user = $entityManager->getRepository('User')->findOneByIdUser($id);
        if($user) {
            $data = [
                'idUser' => $user->getIdUser(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'login' => $user->getLogin(),
                'password' => $user->getPassword(),
                'address' => $user->getAddress(),
                'zipcode' => $user->getZipcode(),
                'city' => $user->getCity(),
                'gender' => $user->getGender(),
                'mail' => $user->getMail(),
                'country' => $user->getCountry(),
                'phone' => $user->getPhone(),
            ];
            $response = $this->createJwT($response, $user);
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $data
            ]));
        }
        else {
            $response = $response->withStatus(401);
        }

        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    // create JWT
    function createJwt (Response $response, User $user) : Response {
        $userid = $user->getIdUser();
        $issuedAt = time();
        $expirationTime = $issuedAt + 60; // jwt valid for 60 seconds from the issued time
        $payload = array(
            'userid' => $userid,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        );
        $token_jwt = JWT::encode($payload,$_ENV['JWT_SECRET'], "HS256");
        $response = $response->withHeader("Authorization", "Bearer {$token_jwt}");
        return $response;
    }
    
}


