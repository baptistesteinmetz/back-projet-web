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
        $login = $body['login'];
        $password = $body['password'];
        $userRepo = $entityManager->getRepository('User');
        $user = $userRepo->findOneBy(array('login' => $login, 'password' => $password)) ?? $userRepo->findOneBy(array('mail' => $login, 'password' => $password));
        if ($user) {
            $response = $this->createJwt($response, $user);                
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
            $results = [
                "success" => true,
                "data" => $data,
            ];
        } else {     
            $results = [
                "success" => false,
                "data" => "Login not possible",
            ];     
        }
        $response->getBody()->write(json_encode($results));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public function register(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $userRepo = $entityManager->getRepository('User');
        $err = false;
        $body = $request->getParsedBody();
        foreach($body as $key => $value){
            ${$key} = $value ?? "";
        }
        $user = $userRepo->findOneBy(array('login' => $login));
        if($user) {
            $err = true;
            $results = [
                "success" => false,
                "data" => "User already exists",
            ];
        }
        else {
            if (!preg_match("/[a-zA-Z0-9]{1,20}/",$password ||$password == ""))  {
                $err=true;
            }
            if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login) ||$login == "")   {
                $err = true;
            }
            if (!preg_match("/[a-zA-Z0-9-]{1,20}/",$address) ||$address == "")  {
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
                $results = [
                    "success" => false,
                    "data" => "There was an error",
                ];
            }
            else {
                $user = new User();
                $user
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setAddress($address)
                ->setCity($city)
                ->setZipcode($zipcode)
                ->setMail($mail)
                ->setPhone($phone)
                ->setCountry($country)
                ->setPassword($password)
                ->setLogin($login)
                ->setGender($gender)
                ;
    
                $entityManager->persist($user);
    
                $entityManager->flush();
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
                $results = [
                    "success" => true,
                    "data" => $data,
                ];
            }

        }
        $response->getBody()->write(json_encode($results));
        $response->withHeader("Content-Type", "application/json");
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
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$password ||$password == ""))  {
            $err=true;
        }
        if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login) ||$login == "")   {
            $err = true;
        }
        if (!preg_match("/[a-zA-Z0-9-]{1,20}/",$address) ||$address == "")  {
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
            $results = [
                "success" => false,
                "data" => "We couldn't update your account."
            ];
        }
        else {
            $user = $userRepo->findOneBy(array('idUser' => $idUser));
            $user
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setAddress($address)
            ->setCity($city)
            ->setZipcode($zipcode)
            ->setMail($mail)
            ->setPhone($phone)
            ->setCountry($country)
            ->setPassword($password)
            ->setLogin($login)
            ->setGender($gender)
            ;
            $results = [
                "success" => true,
                "data" => $body,
            ];
            $entityManager->persist($user);
            $entityManager->flush();
        }
        $response->getBody()->write(json_encode($results));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }


    // get User by ID
    public function getUser(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $id = intval($args['id']);
        $userRepo = $entityManager->getRepository('User');
        $user = $userRepo->findOneBy(array("idUser" => $id));
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
            $results = [
                'success' => true,
                'data' => $data
            ];
        }
        else {
            $results = [
                'success' => false,
            ];
        }
        $response->getBody()->write(json_encode($results));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public function logoff(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $userRepo = $entityManager->getRepository('User');
        $body = $request->getParsedBody();
        $user = $userRepo->findOneBy(array('idUser' => $body['idUser']));
        if($user) {
            $result = [
                'success' => true,
                "data" => "Successfully logged off.",
            ];
        } else {
            $result = [
                'success' => false,
                "data" => "We couldn't log you off.",
            ];
        }
        $response->getBody()->write(json_encode($result));
        $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public function delete(Request $request, Response $response, array $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $userRepo = $entityManager->getRepository('User');
        $body = $request->getParsedBody();
        $user = $userRepo->findOneBy(array('idUser' => $body['idUser']));
        if($user) {
            $entityManager->remove($user);
            $entityManager->flush();
            $result = [
                'success' => true,
                'data' => "Account successfully deleted."
            ];
        } else {
            $result = [
                'success' => false,
                'data' => "There was an error."
            ];
        }
        $response->getBody()->write(json_encode($result));
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


