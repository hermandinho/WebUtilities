<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AboutController
 *
 * @author Manho
 */
class AboutController extends Controller{
    public function index(){
        $this->view->render("about/index");
    }
}
