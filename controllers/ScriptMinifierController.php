<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScriptMinifierController
 *
 * @author Manho
 */
class ScriptMinifierController extends Controller{
    public function index(){
        $this->view->render("minifier/index");
    }
    
    public function minifie(){
        $original_file_infos = array(
            "Size" => $_FILES['script']['size'],
            "Name" => $_FILES['script']['name'],
            "Type" => $_FILES['script']['type'],
            "Error" => $_FILES['script']['error'],
        );
        $tab = file($_FILES['script']['tmp_name'], FILE_IGNORE_NEW_LINES);
        
        $type = $_FILES['script']['type'];
        $minified = "";
        
        echo $minified;
        for($i=0;$i<count($tab);$i++){
            $minified .= (trim($tab[$i]));            
            //echo "$i => ".$tab[$i];
        }
        $fp = fopen("views/minifier/tmp.txt", "w");
        
        fputs($fp, $minified);
        fclose($fp);
        
        $minified_file_info = array("Size" => filesize("views/minifier/tmp.txt"));
        
        $data = array(
            "Original_Infos" => $original_file_infos,
            "Minified_Infos" => $minified_file_info,
            "Minified_Script" => $minified
        );
        
        $this->view->fileInfos = $data;
        
        Session::set("fileInfos", $data);
        header("location: ".$_SERVER['HTTP_REFERER']);
        //$this->view->render("minifier/index");
        //print_r($minified_file_info);
        //echo "<hr>";
        //print_r($original_file_infos);
    }
}
