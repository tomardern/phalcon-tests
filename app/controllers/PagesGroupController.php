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

	public function editAction($id){

		$details = array(
			"name" =>  $this->request->getPut('name', array('striptags', 'string'))
		);

		$group = PagesGroup::findFirst($id);

		if ( $group->save($details) == false) {
			return $this->response->sendError(501,$group->getMessages());		            			
		}

		return $this->response->send(200,"id",$group->getId());
	}


	public function createAction(){

		$details = array(
			"name" =>  $this->request->getPost('name', array('striptags', 'string'))
		);

		$group = new PagesGroup();

		if ( $group->save($details) == false) {
			return $this->response->sendError(501,$group->getMessages());	            			
		}

		return $this->response->send(200,"id",$group->getId());
	}

}   

    //Put is update
    //Delete is delete