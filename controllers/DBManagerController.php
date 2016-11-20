<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBManagerController
 *
 * @author Manho
 */
class DBManagerController extends Controller{
    public function index(){        
        $list = $this->model->listDatabases();
        //var_dump($list);
        $this->view->dataList = $list;
        
        $this->view->render("db_manager/index");
    }
}
