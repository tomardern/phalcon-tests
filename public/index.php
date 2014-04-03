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

    




    /*

    POST /v1/pages/
    GET /v1/pages/14/messages
    POST /v1/pages/15/
    POST /v1/pages/15/activate

    */


    /* -------------------------------------------------
    Pages Router Collection
    ---------------------------------------------------*/
    $pages = new Phalcon\Mvc\Micro\Collection();
    $pages->setHandler('PagesController', true); //This is LazyLoaded
    $pages->setPrefix("/pages");

    //Define our routes
    $pages->get('/', 'indexAction');
    $pages->post('/','createAction');





     $app->mount($pages);

     $app->handle();



     exit;


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






    $app->post("/pages-group", function() use ($app){

        $payload = array();

        //TODO - Sanitise?
        $details = array(
            "name" =>  $app->request->getPost('name', array('striptags', 'string'))
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
    });

    //Put is update
    //Delete is delete



    $app->handle();


    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}