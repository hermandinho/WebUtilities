<?php

class Model{
    function __construct() {
        //echo "Welcome  to the Model Base Class";
        $this->bdd = new DataBase();
    }
}