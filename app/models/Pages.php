<?php

class Pages extends \Phalcon\Mvc\Model {

    //Properties
	protected $id;
	protected $mmc_id;
    protected $created;
    protected $modified;


    public function beforeUpdate() {
        $this->modified = new Phalcon\Db\RawValue('now()');
    }

    //Methods
 	public function initialize() {

 		$this->belongsTo("pages_group_id", "PagesGroup", "id", array(
            "foreignKey" => array(
                "message" => "The pages_group_id does not exist"
        	)
        ));

        //Skips fields/columns on both INSERT/UPDATE operations
        $this->skipAttributes(array('created','mmc_id'));

        //Skip the attributes on INSERT
        $this->skipAttributesOnCreate(array('modified'));
    }

	public function getId(){
		return $this->id;
	}





}