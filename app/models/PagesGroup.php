<?php

class PagesGroup extends \Phalcon\Mvc\Model {

	//Properties
	protected $id;
	protected $name;
	protected $created; //Used by MySQL
	protected $modified;

	//Methods
 	public function initialize() {

 		$this->hasMany("id", "Pages", "pages_group_id");


        //Skips fields/columns on both INSERT/UPDATE operations
        $this->skipAttributes(array('created'));

        //Skips only when inserting
        $this->skipAttributesOnCreate(array('modified'));

    }

    public function beforeUpdate() {
        $this->modified = new Phalcon\Db\RawValue('now()');
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

	
	public function setName($name){
		if (strlen($name) < 10) {
            //throw new \InvalidArgumentException('The name is too short');
        }
        $this->name = $name;
	}


}