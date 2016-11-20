<?php
    class View{
        function __construct() {
            //echo "this is a view <br>";
        }
        
        public function render($name,$noinclude = FALSE){
            if($noinclude == TRUE){
                require 'views/'.$name.".php";
            }  else {
                require 'views/header.php';
                require 'views/navLeft.php';
                require 'views/'.$name.".php";
                require 'views/footer.php';
            }
        }
        
        public function adminRender($name,$noinclude = FALSE){
            if($noinclude == TRUE){
                require 'views/'.$name.".php";
            }  else {
                require 'views/header_a.php';
                require 'views/'.$name.".php";
                require 'views/footer.php';                
            }
        }
    }