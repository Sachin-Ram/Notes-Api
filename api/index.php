<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT']."/api/REST.api.php");
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/Signup.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/User.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/Auth.class.php");

class API extends REST {
    
    public $data = "";
    
    private $db = NULL;
    private $current_call;
    
    public function __construct(){
        parent::__construct();                  // Init parent contructor
        $this->db = Database::getConnection();  // Initiate Database connection
    }
    
    /*
    * Public method for access api.
    * This method dynmically call the method based on the query string
    *
    */
    public function processApi(){
        $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
        if((int)method_exists($this,$func) > 0){
            $this->$func();
        }
        else {
            if(isset($_GET['namespace'])){
                $dir = $_SERVER['DOCUMENT_ROOT'].'/api/apis/'.$_GET['namespace'];
                $methods = scandir($dir);
                //var_dump($methods);
                foreach($methods as $m){
                    if($m == "." or $m == ".."){
                        continue;
                    }
                    $basem = basename($m, '.php');
                    //echo "Trying to call $basem() for $func()\n";
                    if($basem == $func){
                        include $dir."/".$m;
                        $this->current_call = Closure::bind(${$basem}, $this, get_class());
                        $this->$basem();
                    }
                }
            } else {
                //we can even process functions without namespace here.
                $this->response($this->json(['error'=>'methood_not_found']),404);
            }
        }
    }

    public function __call($method, $args){
        if(is_callable($this->current_call)){
            return call_user_func_array($this->current_call, $args);
        } else {
            $this->response($this->json(['error'=>'methood_not_callable']),404);
        }
    }
    
    /*************API SPACE START*******************/
    
    private function about(){
        
        if($this->get_request_method() != "POST"){
            $error = array('method'=> $this->get_request_method(), 'status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
            $error = $this->json($error);
            $this->response($error,406);
        }
        $data = array('method'=> $this->get_request_method(),'version' => $this->_request['version'], 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
        $data = $this->json($data);
        $this->response($data,200);
        
    }
    
    private function test(){
        $data = $this->json(getallheaders());
        $this->response($data,200);
    }
    
    private function gen_hash(){
        $st = microtime(true);
        if(isset($this->_request['pass'])){
            $cost = (int)$this->_request['cost'];
            $options = [
                "cost" => $cost
            ];
            $hash = password_hash($this->_request['pass'], PASSWORD_BCRYPT, $options);
            $data = [
                "hash" => $hash,
                "info" => password_get_info($hash),
                "val" => $this->_request['pass'],
                "verified" => password_verify($this->_request['pass'], $hash),
                "time_in_ms" => microtime(true) - $st
            ];
            $data = $this->json($data);
            $this->response($data,200);
        }
    }
    
    private function verify_hash(){
        if(isset($this->_request['pass']) and isset($this->_request['hash'])){
            $hash = $this->_request['hash'];
            $data = [
                "hash" => $hash,
                "info" => password_get_info($hash),
                "val" => $this->_request['pass'],
                "verified" => password_verify($this->_request['pass'], $hash),
            ];
            $data = $this->json($data);
            $this->response($data,200);
        }
    }
    /*************API SPACE END*********************/
    
    /*
    Encode array into JSON
    */
    private function json($data){
        if(is_array($data)){
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
    
}

// Initiiate Library

$api = new API;
$api->processApi();
?>