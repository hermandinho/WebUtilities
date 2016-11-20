<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBManagerModel
 *
 * @author Manho
 */
class DBManagerModel extends Model{
    public function listDatabases(){
        $list = $this->bdd->query("show databases");
        $donnee = array();
        while ($data = $list->fetch()){
            $donnee[] = $data["Database"];
        }
        return $donnee;
    }
    
}
