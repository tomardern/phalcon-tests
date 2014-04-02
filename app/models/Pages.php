<?php

class Pages extends \Phalcon\Mvc\Model
{


	protected $id;
	protected $mmc_id;


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