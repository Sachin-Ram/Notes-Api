<?php

${basename(__FILE__, '.php')} = function(){
    if($this->isAuthenticated() and isset($this->_request['id'])){
        $n = new Notes($this->_request['id']);
        $data = [
            'id'=>$n->getID(),
            'title' => $n->getTitle(),
            'body' => $n->getBody(),
            'created' => $n->createdAt(),
            'updated' => $n->updatedAt()
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