<?php

class PagesController extends \Phalcon\Mvc\Controller {

    public function indexAction() {

    	$data = array();
        foreach (Pages::find() as $product) {
            $data[] = $product;
        }

       return  $this->response->send(200,"pages",$data);
    }

    public function editAction($id){

    	$page = Pages::findFirst($id);

    	$details = array(
            "name" =>  $this->request->getPut('name'),
            "url" =>  $this->request->getPut('url', array('striptags', 'string')),
            "pages_group_id" => $this->request->getPut('pages_group_id', array('int')),
        );

		if ( $page->save($details) == false) {  
            $messages = array();          
            foreach ($page->getMessages() as $message) {
                $messages[] = array(
                    "message" => $message->getMessage(),
                    "field" => $message->getField(),
                    "type" => $message->getType()
                );                
            }
            return $this->response->sendError(501,$messages);     
        }


        return $this->response->send(200,"id",$page->getId());
    }

    public function viewAction($id){
    	$page = Pages::findFirst($id);
    	return $this->response->send(200,"page",$page);
    }


    public function createAction(){

        $details = array(
            "name" =>  $this->request->getPost('name', array('striptags', 'string')),
            "url" =>  $this->request->getPost('url', array('striptags', 'string')),
            "pages_group_id" => $this->request->getPost('pages_group_id', array('int')),
        );

        $page = new Pages();

        if ( $page->save($details) == false) {
            foreach ($page->getMessages() as $message) {
                $payload["messages"][] = array(
                    "message" => $message->getMessage(),
                    "field" => $message->getField(),
                    "type" => $message->getType()
                );                
            }
            return $this->response->sendError(501,$messages);              
        }

        return $this->response->send(200,"id",$page->getId());
    }



}