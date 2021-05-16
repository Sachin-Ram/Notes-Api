<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Auth.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/User.class.php');

class OAuth {
    private $db;
    private $refresh_token;
    private $access_token;
    private $valid_for = 7200;
    private $username;

    public function __construct($username, $refresh_token = NULL){
        $this->refresh_token = $refresh_token;
        $this->db = Database::getConnection();
        $this->username = $username;
        $u = new User($this->username);
    }

    public function newSession($valid_for = 7200){
        $this->valid_for = $valid_for;
        $this->access_token = Auth::generateRandomHash(32);
        $this->refresh_token = Auth::generateRandomHash(32);
        $query = "INSERT INTO `apis`.`session` (`username`, `access_token`, `refresh_token`, `valid_for`, `reference_token`) 
                                        VALUES ('$this->username', '$this->access_token', '$this->refresh_token', $this->valid_for, 'auth_grant');";
        if(mysqli_query($this->db, $query)){
            return array(
                "access_token" => $this->access_token,
                "valid_for" => $this->valid_for,
                "refresh_token" => $this->refresh_token,
                "type" => 'api'
            );
        } else {
            throw new Exception(mysqli_error($this->db));
        }
    }

    public function refreshAccess(){
        if($this->refresh_token){
            $query = "SELECT * FROM apis.session WHERE refresh_token = '$this->refresh_token';";
            $result = mysqli_query($this->db, $query);
            if($result){
                $data = mysqli_fetch_assoc($result);
                if($data['valid'] == 1){

                } else {
                    throw new Exception("Expired token");
                }
            } else {
                throw new Exception("Invalid request");
            }
        }
    }
}