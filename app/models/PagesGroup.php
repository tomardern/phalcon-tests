<?php

class PagesGroup extends \Phalcon\Mvc\Model
{

	protected $id;
	protected $name;
	protected $created; //Used by MySQL


 	public function initialize() {
        //Skips fields/columns on both INSERT/UPDATE operations
        $this->skipAttributes(array('created'));

        //Skips only when inserting
        $this->skipAttributesOnCreate(array('modified'));

    }


	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		if (strlen($name) < 10) {
            throw new \InvalidArgumentException('The name is too short');
        }
        $this->name = $name;
	}





}