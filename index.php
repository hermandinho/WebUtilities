<?php 
    include './config/config.php';    
    require './libs/Controller.php';
    require './libs/DataBase.php';
    require './libs/Model.php';
    require './libs/Session.php';
    require './libs/View.php';
    require './libs/Bootstrap.php';
    
    $app = new Bootstrap();
    
    $app->run();
