<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/User.class.php');
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

class Auth {

    private $db;
    private $isTokenAuth = false;
    private $loginToken = null;

    public function __construct($username, $password = NULL){
        $this->db = Database::getConnection();
        if($password == NULL){
            //token based auth
            $this->token = $username;
            $this->isTokenAuth = true;
            //we have to validate the token
        } else {
            $this->username = $username; //it might be username or email.
            $this->password = $password;
        }

        if($this->isTokenAuth){
            throw new Exception("Not Implemented");
        } else {
            $user = new User($this->username);
            $hash = $user->getPasswordHash();
            $this->username = $user->getUsername();
            if(password_verify($this->password, $hash)){
                if(!$user->isActive()){
                    throw new Exception("Please check your email and activate your account.");
                }
                $this->loginToken = $this->addSession();
            } else {
                throw new Exception("Password Mismatch");
            }
        }
    }

    public function getAuthToken(){
        return $this->loginToken;
    }

    private function addSession(){
        $token = Auth::generateRandomHash(32);
        $query = "INSERT INTO `apis`.`session` (`username`, `token`) VALUES ('$this->username', '$token');";
        if(mysqli_query($this->db, $query)){
            return $token;
        } else {
            throw new Exception(mysqli_error($this->db));
        }
        
    }

    public static function generateRandomHash($len){
        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
        return bin2hex($bytes);
    }
}