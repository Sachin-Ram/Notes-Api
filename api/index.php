<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");

    class API extends REST {

        public $data = "";

        private $DB_SERVER = "localhost";
        private $DB_USER = "root";
        private $DB_PASSWORD = "";
        private $DB_NAME = "apis";

        private $db = NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            //read database config from ../../env.json
            /*
            file env.json

            {
                "database": "apis",
                "username": "root",
                "password": "",
                "server": "localhost"
            }

            */
            $config_json = file_get_contents('../../env.json');
            $config = json_decode($config_json, true);
            $this->DB_SERVER = $config['server'];
            $this->DB_USER = $config['username'];
            $this->DB_PASSWORD = $config['password'];
            $this->DB_NAME = $config['database'];
            $this->dbConnect();                    // Initiate Database connection
        }

        /*
           Database connection
        */
        private function dbConnect(){
            if ($this->db != NULL) {
				return $this->db;
			} else {
				$this->db = mysqli_connect($this->DB_SERVER,$this->DB_USER,$this->DB_PASSWORD, $this->DB_NAME);
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
            $data = array('version' => $this->_request['version'], 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
            $data = $this->json($data);
            $this->response($data,200);

        }

        private function verify(){
            if($this->get_request_method() == "POST" and isset($this->_request['user']) and isset($this->_request['pass'])){
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
                    $this->response($data,401);
                }
            } else {
                $data = [
                        "status" => "bad_request"
                    ];
                    $data = $this->json($data);
                    $this->response($data,400);
            }
        }

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,200);
        }

        private function request_info(){
            $data = $this->json($_SERVER);
        }

        function generate_hash(){
            $bytes = random_bytes(16);
            return bin2hex($bytes);
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