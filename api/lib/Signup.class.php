<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once('../vendor/autoload.php');
class signup
{
    private $username;
    private $password;
    private $email;
    private $id;
    private $conn;

    public function __construct($username, $password, $email)
    {
        $this->conn=database::getconnection();
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        if($this->userexists($username)){
             throw new Exception("user already exists");  
        }
        else{
        $pass=$this->hashpass();
        $bytes = random_bytes(16);
        $token=bin2hex($bytes);

        $query="INSERT INTO `auth` (`username`, `password`, `email`, `active`, `token`, `signup_time`)
        VALUES ('$username', '$pass', '$email', '0','$token', now())";
       
            if($this->conn->query($query)){
               $this->id=$this->conn->insert_id;
            }
            else{
                throw new Exception("error detected");
            }
    }
    }
    public function hashpass($cost=10){
        $options=[
            "cost" =>$cost
        ];
        $hash=password_hash($this->password, PASSWORD_BCRYPT,$options);
        return $hash;
    }

    public function verificationmail(){
        $file_details=file_get_contents("../../env.json");//gets the configuration details
        $config=json_decode($file_details,true);
        $mail=$config['email_username'];
        $pass=$config['email_pass'];
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function


//Load Composer's autoloader


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

   
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   ='nowsaymyname07@gmail.com';                     //SMTP username
    $mail->Password   = 'ebeqhtywdwcistqb';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('nowsaymyname07@gmail.com', 'Mailer');
    // $mail->addAddress('sachintheking25@gmail.com', 'Joe User');     //Add a recipient
    $mail->addAddress($this->email);               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'test email';//need to add the verification link for the user profiles
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send()){
    return 'Message has been sent';
    }
    else{
        return $mail->ErrorInfo;
    }
}

    public function getinsertid(){
        return $this->id;

    }

    public function userexists($name){
        $sql="SELECT * FROM `auth` WHERE `username` = '$name'";
        $res=$this->conn->query($sql);
        if($res->num_rows==1){
            return true;
        }
        else{
            return false;
        }
    }
}

