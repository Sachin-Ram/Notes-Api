<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");

    class API extends REST {

        public $data = "";

        const DB_SERVER = "localhost";
        const DB_USER = "root";
        const DB_PASSWORD = "";
        const DB = "apis";

        private $db = NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            $this->dbConnect();                    // Initiate Database connection
        }

        /*
           Database connection
        */
        private function dbConnect(){
            if ($this->db != NULL) {
				return $this->db;
			} else {
				$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD, self::DB);
				if (!$this->db) {
					die("Connection failed: ".mysqli_connect_error());
				} else {
					return $this->db;
				}
			}
        }

        /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
        public function processApi(){
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
            if((int)method_exists($this,$func) > 0)
                $this->$func();
            else
                $this->response('',400);                // If the method not exist with in this class, response would be "Page not found".
        }

        /*************API SPACE START*******************/

        private function about(){

            if($this->get_request_method() != "POST"){
                $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
                $error = $this->json($error);
                $this->response($error,406);
            }
            $data = array('version' => '0.1', 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
            $data = $this->json($data);
            $this->response($data,200);

        }

        private function verify(){
            $user = $this->_request['user'];
            $password =  $this->_request['pass'];

            $flag = 0;
            if($user == "admin"){
                if($password == "adminpass123"){
                    $flag = 1;
                }
            }

            if($flag == 1){
                $data = [
                    "status" => "verified"
                ];
                $data = $this->json($data);
                $this->response($data,200);
            } else {
                $data = [
                    "status" => "unauthorized"
                ];
                $data = $this->json($data);
                $this->response($data,403);
            }
        }

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,200);
        }

        private function get_current_user(){
            $username = $this->is_logged_in();
            if($username){
                $data = [
                    "username"=> $username
                ];
                $this->response($this->json($data), 200);
            } else {
                $data = [
                    "error"=> "unauthorized"
                ];
                $this->response($this->json($data), 403);
            }
        }

        private function logout(){
            $username = $this->is_logged_in();
            if($username){
                $headers = getallheaders();
                $auth_token = $headers["Authorization"];
                $auth_token = explode(" ", $auth_token)[1];
                $query = "DELETE FROM session WHERE session_token='$auth_token'";
                $db = $this->dbConnect();
                if(mysqli_query($db, $query)){
                    $data = [
                        "message"=> "success"
                    ];
                    $this->response($this->json($data), 200);
                } else {
                    $data = [
                        "user"=> $this->is_logged_in()
                    ];
                    $this->response($this->json($data), 200);
                }
            } else {
                $data = [
                    "user"=> $this->is_logged_in()
                ];
                $this->response($this->json($data), 200);
            }
        }

        private function user_exists(){
            if(isset($this->_request['data'])){
                $data = $this->_request['data'];
                $db = $this->dbConnect();
                $result = mysqli_query($db, "SELECT id, username, mobile FROM users WHERE id='$data' OR username='$data' OR mobile='$data'");
                if($result){
                    $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $this->response($this->json($result), 200);
                } else {
                    $data = [
                        "error"=>"user_not_found"
                    ];
                    $this->response($this->json($data), 404);
                }

            } else {
                $data = [
                    "error"=>"expectation_failed"
                ];
                $this->response($this->json($data), 417);
            }
        }

        private function signup(){
            if($this->get_request_method() != "POST"){
                $data = [
                    "error"=>"method_not_allowed"
                ];
                $this->response($this->json($data), 405);
            }
            if(isset($this->_request['username']) and isset($this->_request['password']) and isset($this->_request['mobile'])){
                $username = $this->_request['username'];
                $password = $this->_request['password'];
                $mobile = $this->_request['mobile'];

                $query = "INSERT INTO users (username, password, mobile) VALUES ('$username', '$password', '$mobile');";

                $db = $this->dbConnect();
                $result = mysqli_query($db, $query);
                if($result){
                    $data = [
                        "message"=>"success"
                    ];
                    $this->response($this->json($data), 201);
                } else {
                    $data = [
                        "error"=>"internal_server_error"
                    ];
                    $this->response($this->json($data), 500);
                }
            } else {
                $data = [
                    "error"=>"expectation_failed"
                ];
                $this->response($this->json($data), 417);
            }
        }

        private function login(){
            if($this->get_request_method() != "POST"){
                $data = [
                    "error"=>"method_not_allowed"
                ];
                $this->response($this->json($data), 405);
            }

            if(isset($this->_request['username']) and isset($this->_request['password'])){
                $db = $this->dbConnect();
                $username = $this->_request['username'];
                $password = $this->_request['password'];
                $result = mysqli_query($db, "SELECT * FROM users WHERE (id='$username' OR username='$username' OR mobile='$username') AND password = '$password'");
                $d = mysqli_fetch_assoc($result);
                if($d){
                    $userid = $d['id'];
                    $token = $this->generate_hash();
                    $query = "INSERT INTO `session` (session_token, is_valid, user_id) VALUES ('$token', '1', '$userid');";
                    if(mysqli_query($db, $query)){
                        $data = [
                            "message"=>"success",
                            "token"=>$token
                        ];
                        $this->response($this->json($data), 201);
                    } else {
                        $data = [
                            "error"=>"internal_server_error",
                            "message"=>mysqli_error($db)
                        ];
                        $this->response($this->json($data), 500);
                    }
                } else {
                    $data = [
                        "error"=>"invalid_credentials"
                    ];
                    $this->response($this->json($data), 404);
                }
            } else {
                $data = [
                    "error"=>"expectation_failed"
                ];
                $this->response($this->json($data), 417);
            }
        }

        function generate_hash(){
            $bytes = random_bytes(16);
            return bin2hex($bytes);
        }

        function is_logged_in(){
            $headers = getallheaders();
            if(isset($headers["Authorization"])){
                $auth_token = $headers["Authorization"];
                $auth_token = explode(" ", $auth_token)[1];

                $query = "SELECT * FROM session WHERE session_token='$auth_token'";
                $db = $this->dbConnect();
                $_result = mysqli_query($db, $query);
                $d = mysqli_fetch_assoc($_result);
                if($d){
                    $data = $d['user_id'];
                    $result = mysqli_query($db, "SELECT id, username, mobile FROM users WHERE id='$data' OR username='$data' OR mobile='$data'");
                    if($result){
                        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        return $result["username"];
                    } else {
                        return false;
                    }

                } else {
                    return false;
                }
            } else {
                return false;
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