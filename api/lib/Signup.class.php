<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
//TODO Homework: find why ../vendor? it is the same reason why we use ../../env.json in config.

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
        if($this->userExists()){
            throw new Exception("User already exists");
        }
        $bytes = random_bytes(16);
        $this->token = $token = bin2hex($bytes); //to verify users over email.
        $password = $this->hashPassword();
        //Homework - make a proper flow to throw username already exists
        $query = "INSERT INTO `apis`.`auth` (`username`, `password`, `email`, `active`, `token`) VALUES ('$username', '$password', '$email', 0, '$token');";
        if(!mysqli_query($this->db, $query)){
            throw new Exception("Unable to signup, user account might already exist.");
        } else {
            $this->id = mysqli_insert_id($this->db);
            $this->sendVerificationMail();
        }
    }
    
    function sendVerificationMail(){
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../env.json');
        $config = json_decode($config_json, true);
        $token = $this->token;
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("noreply@selfmade.ninja", "API Course by Selfmade");
        $email->setSubject("Verify your account");
        $email->addTo($this->email, $this->username);
        $email->addContent("text/plain", "Please verify your account at: https://api1.selfmade.ninja/verify?token=$token");
        $email->addContent(
            "text/html", "<strong>Please verify your account by <a href=\"https://api1.selfmade.ninja/verify?token=$token\">clicking here</a> or open this URL manually: <a href=\"https://api1.selfmade.ninja/verify?token=$token\">https://api1.selfmade.ninja/verify?token=$token</a></strong>"
        );
        $sendgrid = new \SendGrid($config['email_api_key']);
        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
        
    }
    
    public function getInsertID(){
        return $this->id;
    }
    
    public function userExists(){
        //TODO: Write the code to check if user exists.
        return false;
    }
    
    public function hashPassword($cost = 10){
        //echo $this->password;
        $options = [
            "cost" => $cost
        ];
        return password_hash($this->password, PASSWORD_BCRYPT, $options);
    }

    public static function verifyAccount($token){
        $query = "SELECT * FROM apis.auth WHERE token='$token';";
        $db = Database::getConnection();
        $result = mysqli_query($db, $query);
        if($result and mysqli_num_rows($result) == 1){
            $data = mysqli_fetch_assoc($result);
            if($data['active'] == 1){
                throw new Exception("Already Verified");
            }
            mysqli_query($db, "UPDATE `apis`.`auth` SET `active` = '1' WHERE (`token` = '$token');");
            return true;
        } else {
            return false;
        }
    }
    
}