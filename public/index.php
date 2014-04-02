<?php

try {



    /**
     * Read the configuration
     */
    $config = new Phalcon\Config\Adapter\Ini(__DIR__ . '/../app/config/config.ini');


    //Register an autoloader 
    //TODO: Should use http://docs.phalconphp.com/en/latest/reference/loader.html#registering-namespaces
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/'
    ))->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();

    
    //Setup the database service
    $di->set('db', function() use ($config) {
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->name
        ));
    });



    //Setup a base URI so that all generated URIs
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/cms-api/');
        return $url;
    });


    $app = new \Phalcon\Mvc\Micro($di);

    


    //Retrieves all robots
    $app->get('/pages', function() use ($app) {

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
    });

    $app->get('/pages-group', function() use ($app) {

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
    });




    $app->post("/pages", function() use ($app){

        $payload = array();

        //TODO - Sanitise?
        $details = array(
            "name" =>  $app->request->getPost('name', array('striptags', 'string')),
            "url" =>  $app->request->getPost('url', array('striptags', 'string')),
            "pages_group_id" => $app->request->getPost('pages_group_id', array('int', 'string')),
        );

        //$contact->created_at = new Phalcon\Db\RawValue('now()');

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
    });



    $app->post("/pages-group", function() use ($app){

        $payload = array();

        //TODO - Sanitise?
        $details = array(
            "name" =>  $app->request->getPost('name', array('striptags', 'string'))
        );

        //$contact->created_at = new Phalcon\Db\RawValue('now()');

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
    });

    //Put is update
    //Delete is delete



    $app->handle();


    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}