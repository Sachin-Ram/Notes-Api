<?php

${basename(__FILE__, '.php')} = function(){
    if($this->get_request_method() == "POST" and $this->isAuthenticated() and isset($this->_request['id'])){
        $f = new Folder($this->_request['id']);
        if($f->delete()){
            $data = [
                'message' => 'success',
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        } else {
            $data = [
                'message' => 'error',
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
       
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};