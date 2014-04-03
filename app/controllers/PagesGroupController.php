<?php

class PagesGroupController extends \Phalcon\Mvc\Controller {


	function indexAction() {

		$data = array();
		foreach (PagesGroup::find() as $product) {
			$data["data"][] = array(
				"id" => $product->getId(),
				"name" => $product->getName()
			);
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

		//TODO - Sanitise?
		$details = array(
			"name" =>  $this->request->getPost('name', array('striptags', 'string'))
		);


		$group = new PagesGroup();

		if ( $group->save($details) == false) {
			$payload["status"] = false;
			foreach ($group->getMessages() as $message) {
				$payload["messages"][] = array(
					"message" => $message->getMessage(),
					"field" => $message->getField(),
					"type" => $message->getType()
				);                
			}            
		} else {
			$payload["id"] = $group->getId();
			$payload["status"] = true;
		}

		echo json_encode($payload);
	}

}   

    //Put is update
    //Delete is delete