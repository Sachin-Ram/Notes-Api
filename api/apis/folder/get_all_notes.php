<?php

${basename(__FILE__, '.php')} = function(){
    if($this->get_request_method() == "POST" and $this->isAuthenticated() and isset($this->_request['id'])){
        $f = new Folder($this->_request['id']);
        $data = [
            'count' => $f->countNotes(),
            'notes' => $f->getAllNotes()
        ];
        $data = $this->json($data);
        $this->response($data, 200);
    } else {
        $data = [
            "error" => "Bad request"
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
};