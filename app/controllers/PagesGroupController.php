<?php

class PagesGroupController extends \Phalcon\Mvc\Controller {


	function indexAction() {

		$groups = array();
		foreach (PagesGroup::find() as $product) {
			$groups[] = array(
				"id" => $product->getId(),
				"name" => $product->getName()
			);
		}

		return $this->response->send(200,"groups",$groups);
	}

	public function createAction(){

		$details = array(
			"name" =>  $this->request->getPost('name', array('striptags', 'string'))
		);

		$group = new PagesGroup();

		if ( $group->save($details) == false) {
			$messages = array();
			foreach ($group->getMessages() as $message) {
				$messages[] = array(
					"message" => $message->getMessage(),
					"field" => $message->getField(),
					"type" => $message->getType()
				);                
			}            
			return $this->response->send(501,"errors",$messages);		
		}

		return $this->response->send(200,"id",$group->getId());
	}

}   

    //Put is update
    //Delete is delete