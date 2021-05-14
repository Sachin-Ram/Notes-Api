<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

class User {
    private $db;
    private $user;

    public function __construct($username){
        $this->username = $username;
        $this->db = Database::getConnection();
        $query = "SELECT * FROM auth WHERE username='$this->username' OR email='$this->username'";
        //echo $query;
        $result = mysqli_query($this->db, $query);
        if(mysqli_num_rows($result) == 1){
            $this->user = mysqli_fetch_assoc($result);
        } else {
            throw new Exception("User not found");
        }
    }

    public function getUsername(){
        return $this->user['username'];
    }

    public function getPasswordHash(){
        return $this->user['password'];
    }

    public function getEmail(){
        return $this->user['email'];
    }

    public function isActive(){
        return $this->user['active'];
    }
}