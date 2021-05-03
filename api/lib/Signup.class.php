<?php

require_once('Database.class.php');

class Signup {

    private $username;
    private $password;
    private $email;

    private $db;

    public function __construct($username, $password, $email){
        $this->db = Database::getConnection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function getInsertID(){

    }

    public function hashPassword(){
        return password_hash($this->$password, PASSWORD_BCRYPT);
    }

}