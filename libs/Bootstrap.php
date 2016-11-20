<?php

/*
 * Bootstrap class By Hermanho :)
 */

class Bootstrap {

    function __construct() {
        
    }

    public function run() {
        $l = isset($_GET["url"]) ? $_GET["url"] : null;
        $url = rtrim($l, "/");
        $url = rtrim($url, "'");
        //$url = rtrim($url,"--");
        Session::init();
        $url = explode("/", $url);

        $file = 'controllers/' . ucfirst($url[0]) . "Controller.php"; //Chemin du controlleur

        if (empty($url[0])) {// si aucun controlleur n'est passé en URL, charger le Controlleur par defaut IndexController
            require_once 'controllers/IndexController.php';
            $controller = new IndexController();
            $controller->index();
            return FALSE;
        }

        if (file_exists($file)) {
            require $file;
        } else {
            //throw new Exception("Le Controller <strong>".ucfirst($url[0])."Controller.php</strong> est introuvable");
            require_once 'controllers/ErrorController.php';
            $error = new ErrorController("no_controller");
            return FALSE;
        }

        $ctrl = ucfirst($url[0]) . "Controller";
        $controller = new $ctrl;
        $controller->loadModel($url[0]);

        if (isset($url[2])) {
            $param = $url[2];
            $param = rtrim($param, "--");
            $param = rtrim($param, "'");
            $controller->{$url[1]}($param);
            return FALSE;
        } else {
            if (isset($url[1])) {
                if (method_exists($controller, $url[1])) {
                    $controller->{$url[1]}();
                } else {
                    require 'controllers/ErrorController.php';
                    $error = new ErrorController("no_method");
                }

                return FALSE;
            }
        }
        $controller->index();
    }

}
