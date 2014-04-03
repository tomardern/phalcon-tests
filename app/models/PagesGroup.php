<?php

class PagesGroup extends \Phalcon\Mvc\Model
{

	protected $id;
	protected $name;
	protected $created; //Used by MySQL
	protected $modified;

 	public function initialize() {

 		$this->hasMany("id", "Pages", "pages_group_id");


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


	//This can be done here too
	public function beforeSave() {
        
        if (strlen($name) < 10) {
            //throw new \InvalidArgumentException('The name is too short beforeSave()');
        }


    }

	public function beforeUpdate() {
        //Set the modification date
        $this->modified = new Phalcon\Db\RawValue('now()');
    }



	public function setName($name){
		if (strlen($name) < 10) {
            throw new \InvalidArgumentException('The name is too short');
        }
        $this->name = $name;
	}


	public function setModified(){
		$this->modified = new Phalcon\Db\RawValue('now()');
	}


}