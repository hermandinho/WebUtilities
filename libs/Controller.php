<?php
    class Controller{
        function __construct() {
            //echo "Main Controller <br>";
            $this->view = new View();            
        }
        
        public function loadModel($name){
            $path = "models/".ucfirst($name)."Model.php";
            if(file_exists($path)){
                require_once $path;
                $modelName = ucfirst($name)."Model";
                $this->model = new $modelName();
            }
        }
    }