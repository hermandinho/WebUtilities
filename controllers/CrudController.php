<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CrudController
 *
 * @author Manho
 */
require "ClassManagerController.php";

class CrudController extends ClassManagerController{
    public function index(){
        $this->loadModel("ClassManager");
        $data = $this->model->describeDatabase(Session::get("selected_db"));
        //var_dump($data);
        $this->view->DBFields = $data;        
        $this->view->render("crud/index");
    }
    
    public function generate(){
        if($_POST["Tour"] == 0){
            echo "Creating Project structure ...";
            $this->model->createProjectStructure($_POST['projectName']);
        }
        $this->loadModel("ClassManager");
        parent::generate(GENERATED_CRUD.$_POST['projectName']."/controllers/",GENERATED_CRUD.$_POST['projectName']."/models/",TRUE);
    }
}
