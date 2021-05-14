<?php

${basename(__FILE__, '.php')} = function(){
    if($this->get_request_method() == "POST" and isset($this->_request['username']) and isset($this->_request['email']) and isset($this->_request['password'])){
        $username = $this->_request['username'];
        $email = $this->_request['email'];
        $password = $this->_request['password'];

        try{
            $s = new Signup($username, $password, $email);
            $data = [
                "message" => "Signup success",
                "userid" => $s->getInsertID()
            ];
            $this->response($this->json($data), 200);
        } catch(Exception $e) {
            $data = [
                "error" => $e->getMessage()
            ];
            $this->response($this->json($data), 409);
        }
         
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};