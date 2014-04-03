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



    Class p8response extends Phalcon\Http\Response {
        function helloworld(){
            echo "Hello world";


        }
    }


    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();


    $di->set('response', function() use ($config) {
        return new p8response();
    });


    
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
    /*
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/cms-api/');
        return $url;
    });
    */
    
    $app = new \Phalcon\Mvc\Micro($di);



 


    /* -------------------------------------------------
    Pages Router Collection
    ---------------------------------------------------*/
    $pages = new Phalcon\Mvc\Micro\Collection();

    //Define our routes
    $pages->get('/', 'indexAction');
    $pages->post('/','createAction');
    $pages->get('/{id}','viewAction');
    $pages->put('/{id}','editAction');
    $pages->get('/missing','missingAction');

    //Set up our handler
    $pages->setHandler('PagesController', true); //This is LazyLoaded
    $pages->setPrefix("/pages");    
    $app->mount($pages);

    /* -------------------------------------------------
    Pages Group Router Collection
    ---------------------------------------------------*/
    $pagesGroup = new Phalcon\Mvc\Micro\Collection();

    //Define out routes
    $pagesGroup->get('/','indexAction');
    $pagesGroup->post('/','createAction');

    //Set up our handler
    $pagesGroup->setHandler('PagesGroupController',true);
    $pagesGroup->setPrefix('/pages-group');
    $app->mount($pagesGroup);




    /* -------------------------------------------------
    Not Found/Default route
    ---------------------------------------------------*/
    $app->notFound(function () use ($app) {
        $app->response->setStatusCode(404, "Not Found")->sendHeaders();

        $app->response->helloworld();

        echo "404 - Not Found";
    });

    

    $app->handle();

    exit;

    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}