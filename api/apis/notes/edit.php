<?php

${basename(__FILE__, '.php')} = function(){
    if($this->get_request_method() == "POST" and $this->isAuthenticated() and isset($this->_request['id']) and isset($this->_request['title']) and isset($this->_request['body'])){
        $n = new Notes($this->_request['id']);
        $n->setTitle($this->_request['title']);
        $n->setBody($this->_request['body']);
        $data = [
            'id' => $n->getId(),
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