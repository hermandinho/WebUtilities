<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClassManagerController
 *
 * @author Manho
 */
class ClassManagerController extends Controller{
    public function index(){
        $data = $this->model->describeDatabase(Session::get("selected_db"));
        //var_dump($data);
        $this->view->DBFields = $data;
        
        $this->view->render("class_manager/index");
    }
    
    public function generate($pathC = GENERATED_CLASSES,$pathM = GENERATED_MODELS,$forCrud=FALSE){
        $this->generateClass($_POST['data'],TRUE,$pathC,$pathM,$forCrud);        
    }
    
    private function generateClass($name,$whitModel = TRUE,$pathC,$pathM,$isCrud){
        //require 'controllers/FormBuilderController.php';        
        if($whitModel ==TRUE){
            $data = $this->model->buildClass($name,TRUE,$pathC,$pathM,$isCrud);
        }else{
            $data = $this->model->buildClass($name,false);
        }        
        return $data;
    }
}
