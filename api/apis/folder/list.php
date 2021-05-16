<?php

${basename(__FILE__, '.php')} = function(){
    if($this->isAuthenticated()){
        $data = Folder::getAllFolders();
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