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
    $di->set('db', function(){
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
    $app->get('/api/robots', function() use ($app) {

        echo "hello world";
        exit;

        $data = array();
        foreach (Robots::find() as $product) {
            $data["data"][] = $product;
        }
        $data["status"] = true;
        $data["msg"] = array(
            "There has been an error",
            "Another error should go here",
            "Plus another error"
        );

        return $data;
    });








    $app->handle();


    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}