<?php

class PagesController extends \Phalcon\Mvc\Controller
{

    public function indexAction() {

    	$data = array();
        foreach (Pages::find() as $product) {
            $data["data"][] = $product;
        }
        $data["status"] = true;
        $data["msg"] = array(
            "There has been an error",
            "Another error should go here",
            "Plus another error"
        );

        echo json_encode($data);
    }


    public function createAction(){
    	$payload = array();

        $details = array(
            "name" =>  $this->request->getPost('name', array('striptags', 'string')),
            "url" =>  $this->request->getPost('url', array('striptags', 'string')),
            "pages_group_id" => $this->request->getPost('pages_group_id', array('int')),
        );

        $page = new Pages();

        if ( $page->save($details) == false) {
            $payload["status"] = false;
            foreach ($page->getMessages() as $message) {
                $payload["messages"][] = array(
                    "message" => $message->getMessage(),
                    "field" => $message->getField(),
                    "type" => $message->getType()
                );                
            }            
        } else {
            $payload["id"] = $page->getId();
            $payload["status"] = true;
        }

        echo json_encode($payload);
    }



    public function showAction() {
    	echo "hello world";
    }

}