<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormBuilderModel
 *
 * @author Manho
 */
class FormBuilderModel extends Model{
    //echo "La mort";
    
    public function __construct() {
        //echo "Loool in FormBuilderModel :)";
        parent::__construct();
    }


    public function helo(){
        echo "Ca roule ";
    }
}
