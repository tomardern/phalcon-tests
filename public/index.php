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
    $pages->get('/{id}','viewAction');
    $pages->put('/{id}','editAction');
    $pages->get('/missing','missingAction');
    
    $app->mount($pages);

    /* -------------------------------------------------
    Pages Group Router Collection
    ---------------------------------------------------*/
    $pagesGroup = new Phalcon\Mvc\Micro\Collection();
    $pagesGroup->setHandler('PagesGroupController',true);
    $pagesGroup->setPrefix('/pages-group');

    //Define out routes
    $pagesGroup->get('/','indexAction');
    $pagesGroup->post('/','createAction');

    $app->mount($pagesGroup);



    $app->notFound(function () use ($app) {
        $app->response->setStatusCode(404, "Not Found")->sendHeaders();
        echo "404 - Not Found";
    });

    

    $app->handle();

    exit;

    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}