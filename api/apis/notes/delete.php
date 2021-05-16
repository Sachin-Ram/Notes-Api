<?php

${basename(__FILE__, '.php')} = function(){
    if($this->isAuthenticated() and isset($this->_request['id'])){
        $n = new Notes($this->_request['id']);
        if($n->delete()){
            $data = [
                'message'=> 'success',
            ];
            $data = $this->json($data);
            $this->response($data, 200);
        } else {
            $data = [
                "error" => "Cannot delete"
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