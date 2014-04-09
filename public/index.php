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


    $di->set('response', function() use ($config) {
        require "../app/services/cmsresponse.php";
        return new cmsresponse();
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
    $pages->setPrefix("/pages");
    $pages->get('/', 'indexAction');
    $pages->post('/','createAction');
    $pages->get('/{id}','viewAction');
    $pages->put('/{id}','editAction');
    $pages->get('/missing','missingAction');

    //Set up our handler
    $pages->setHandler('PagesController', true); //This is LazyLoaded    
    $app->mount($pages);


    /* -------------------------------------------------
    Pages Group Router Collection
    ---------------------------------------------------*/
    $pagesGroup = new Phalcon\Mvc\Micro\Collection();

    //Define out routes
    $pagesGroup->setPrefix('/pages-group');
    $pagesGroup->get('/','indexAction');
    $pagesGroup->post('/','createAction');
    $pagesGroup->put('/{id}','editAction');

    //Set up our handler
    $pagesGroup->setHandler('PagesGroupController',true);    
    $app->mount($pagesGroup);




    /* -------------------------------------------------
    Not Found/Default route
    ---------------------------------------------------*/
    $app->notFound(function () use ($app) {
        return $app->response->send(404);
    });

    $app->handle();

    exit;

    

    //echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
    return $app->response->sendError(501,$e->getMessage());   
     echo "PhalconException: ", $e->getMessage();
}