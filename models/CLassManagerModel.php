<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CLassManagerModel
 *
 * @author Manho
 */
class CLassManagerModel extends Model{
    public function describeDatabase($db){       
        if($this->useDataBase()){
            $showTables = $this->bdd->query("SHOW TABLES");
            $donnee = array();
            while ($data = $showTables->fetch()){
                $donnee[] = $data[0];
            }
            return $donnee;
        }else{
            return array("status"=>FALSE,"message"=>"Can not use Database ".$db);
        }
    }
    
    public function getTableInfos($table){
        //echo "DESC ".$table;        
        $this->useDataBase();
        $describe = $this->bdd->query("DESC ".$table);
        //echo $describe->rowCount();
        if($describe){
            while ($data = $describe->fetch()){
                $donnee[] = array(
                    "Name" => $data["Field"],
                    "Type" => $data["Type"],
                    "Key" => $data["Key"],
                );                
            }            
        }
        //$this->buildClass($table);
        return $donnee;
    }
    
    private function useDataBase(){
        $useDb = "USE ".Session::get("selected_db");
        $R_Use = $this->bdd->query($useDb);  
        if($R_Use){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function buildClass($className,$whitModel = TRUE,$pathC,$pathM,$isCrud){
        //$fp = fopen(GENERATED_CLASSES."".ucfirst($className), "a+");
        $file = ucfirst($className)."Controller.php"; 
        echo "Creating file $file...";
        if(!file_exists($pathC.$file)){
            touch($pathC.$file);            
        }
        echo " ok\n";
        echo "Opening file $file ...";
        $fp = fopen($pathC.$file, "w");
        echo " ok\n";
        
        echo "\tWriting file content ... ";
        $fields = $this->getTableInfos($className);
        $getters = null;
        $setters = null;
        $actions = null;
        $forCrud = ($isCrud == TRUE)?"extends Controller":"";
        
        $actions .= "
\t\tpublic function index(){
    \t\t\$this->display();
\t\t}

\t\tpublic function add(){
        \tif(\$_SERVER['REQUEST_METHOD'] == 'GET'){
            \t//Display form here
            \t\$this->view->render('$className/add');
        \t}elseif(\$_SERVER['REQUEST_METHOD'] == 'POST'){
            \t//Proccess Form here
            \t\$obj = new ".ucfirst($className)."Controller(\$_POST);
             \n\t//\tvar_dump(\$obj);
            \t\$action = \$this->model->add(\$obj);

            \n\techo \$action['message'];
            \n\techo \"<a href=\".URL.\"".ucfirst($className).">Back</a>\";
            
        \t}
\t\t}

\t\tpublic function edit(\$id=null){
    
        \tif(\$_SERVER['REQUEST_METHOD'] == 'GET'){
            \t//Display form here
            \t\t\$data = \$this->model->getInfos(\$id);
                \t\t\$this->view->data = \$data;
                \t\t\$this->view->render('$className/edit'); 
        \t}elseif(\$_SERVER['REQUEST_METHOD'] == 'POST'){
            \t//Proccess Form here            
            //var_dump(\$_POST);
            \t\$obj = new ".ucfirst($className)."Controller(\$_POST);
             \n\t//\tvar_dump(\$obj);
            \t\$action = \$this->model->edit(\$obj);
            
            \n\t\t\t\tif(\$action['status']) {
            \t\t\t\techo \$action['message'];
            \t\t\t\techo \"<a href=\".URL.\"".ucfirst($className).">Back</a>\";
            \n\t\t\t\t}
        \t}        
\t\t}


\t\tpublic function delete(\$id){
    //\t\t\$this->view->Id = \$id;
    //\t\$obj = new ".ucfirst($className)."Controller(\$_GET);
    \t\t\t\$action = \$this->model->delete(\$id);
    \t\t\$this->view->message = \$action;
    \t\t\$this->view->render('$className/delete');
\t\t}

\t\tpublic function display(\$page = 1){
    \t\t\$this->view->getData = \$this->model->listAll();
    \t\t\$this->view->page = \$page;
    \t\t\$this->view->totalData= \$this->model->getTotalNumber();
    
    \t\t\$this->view->render('$className/display');
\t\t}
                ";
        
        $content = "
<?php
    class ".ucfirst($className)."Controller $forCrud{
    ";
          for($i=0;$i<count($fields);$i++){
              $content .= "    private \$".$fields[$i]["Name"].";\n    ";
              $getters .= "\n\t\tpublic function get".  ucfirst($fields[$i]["Name"])."(){\n\t\t\treturn \$this->".$fields[$i]["Name"].";\n\t\t}";
              $setters .= "\n\t\tpublic function set".  ucfirst($fields[$i]["Name"])."(\$arg){\n\t\t\t\$this->".$fields[$i]["Name"]." = \$arg;\n\t\t}";
          }
     $content .= "
         \n\t\tpublic function __construct(\$data = ''){
            //todo stuff here :) 
            \tparent::__construct(\$data);
        }
        $actions
        
        $getters
        $setters
        
    }
    ";
        fputs($fp, trim($content));
        echo " ok\n";
        if($whitModel == TRUE){
            $this->buildModel($className,$pathM,$isCrud);
        }
        echo " ------------------------------------------------------------------------------------------\n\n";
    }
    
    private function returnAddQuery($table,$fields){
        $r_add = "\$add = \"INSERT INTO ".$table;
        $str = "";
        $getters = "";
        $params = "";
        for($i=0;$i<count($fields);$i++){
            if($fields[$i]['Key'] != "PRI"){
                $str .= $fields[$i]["Name"];
                $getters .= "\$args->get".  ucfirst($fields[$i]["Name"])."()";
                $params .= "?";
                if($i <  count($fields)-1){ $str .= ","; $params .= ","; $getters .= ","; }               
            }                
        }
        $r_add .= "(".$str.") VALUES($params) \";\n";
        
        $r_add .= "\t\t\t\$R_Add = \$this->bdd->prepare(\$add);\n";
        $r_add .= "\t\t\t\$R_Add->execute(array($getters));\n\n";
        
        $r_add .= "\t\t\tif(\$R_Add){\n\t\t\t\treturn array('status'=>TRUE,'message'=>\"<div class='tile-area-darkGreen'>Data Succesfully Saved !!!</div> \");\n\t\t\t}";
        $r_add .= " else{\n\t\t\t\treturn array('status'=>FALSE,'message'=>\"<div class='tile-area-darkRed'>Sorry, an error occured while saving data !!!</div> \");\n\t\t\t}";
        
        return $r_add;        
    }
    
    private function returnEditQuery($table,$fields){
        $r = "\$edit = \"UPDATE ".$table." SET ";
        $str = "";
        $getters = "";
        $params = "";
        $pk = '';
        $getPk = '';
        for($i=0;$i<count($fields);$i++){
            if($fields[$i]['Key'] != "PRI"){
                $f = $fields[$i]["Name"];
                $getters = "\$args->get".  ucfirst($fields[$i]["Name"])."()";
                $params .= "$f = '\".".$getters.".\"'";
                if($i<count($fields)-1){ $params .= ","; }
            }else{
                $pk = $fields[$i]['Name'];
                $getPk = "\$args->get".ucfirst($fields[$i]["Name"])."()";
            }
        }
        
        $r .= $params." WHERE ".ucfirst($pk)." = \".".$getPk.";\n";
        
        
        $r .= "\t\t\t\$R_Edit = \$this->bdd->query(\$edit);\n";
        //$r .= "\t\t\t\$R_Add->execute(array($getters));\n\n";
        $r .= "\t\t//echo \$edit;";
        $r .= "\n\t\t\tif(\$R_Edit){\n\t\t\t\treturn array('status'=>TRUE,'message'=>\"<div class='tile-area-darkGreen'>Data Succesfully Updated !!!</div> \");\n\t\t\t}";
        $r .= " else{\n\t\t\t\treturn array('status'=>FALSE,'message'=>\"<div class='tile-area-darkRed'>Sorry, an error occured while updating data !!!</div> \");\n\t\t\t}";
        
        return $r;
    }
    
    private function returnDeleteQuery($table,$fields){
        $r = "\$del = \"DELETE FROM ".$table." WHERE ";

        for($i=0;$i<count($fields);$i++){
            if($fields[$i]['Key'] == "PRI"){
                $pk = $fields[$i]['Name'];
                //$getPk = $getters = "\$args->get".  ucfirst($fields[$i]["Name"])."()";
                //$getPk = 
                break;
            }
        }
        
        $r .= "$pk = \".\"\$id\";\n";
        
        $r .= "//\t\t\techo \$del;";
        
        $r .= "\n\t\t\t\$R_Del = \$this->bdd->query(\$del);\n";
        //$r .= "\t\t\t\$R_Add->execute(array($getters));\n\n";
        
        $r .= "\t\t\tif(\$R_Del){\n\t\t\t\treturn array('status'=>TRUE,'message'=>\"<div class='tile-area-darkGreen'>Data Succesfully Deleted !!!</div> \");\n\t\t\t}";
        $r .= " else{\n\t\t\t\treturn array('status'=>FALSE,'message'=>\"<div class='tile-area-darkRed'>Sorry, an error occured while Deleting data !!!</div> \");\n\t\t\t}";
        
        return $r;
    }
    
    public function getForeingTables($table){
        /*USE information_schema;
            select table_name,column_NAME,CONSTRAINT_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
            from KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = 'demodb'
            AND TABLE_NAME = 't3' 
            and REFERENCED_COLUMN_NAME is not null */
        
        $useInfoSchema = $this->bdd->query("USE information_schema");
        $r = "select table_name,column_NAME,CONSTRAINT_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
            from information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = '".Session::get("selected_db")."'
            AND TABLE_NAME = '".$table."'
            and REFERENCED_COLUMN_NAME is not null";
        //echo $r;
        $reqData = $this->bdd->query($r);
        
        $donnee = array();
        while($data = $reqData->fetch()){
            $donnee[] = array(
                "Foreign_table" => $data["REFERENCED_TABLE_NAME"],
                "Foreign_key" => $data["REFERENCED_COLUMN_NAME"],
                "Column_Name" => $data['column_NAME']
            );
        }  //var_dump($donnee);      
        //return $reqData->fetchAll();        
        return $donnee;
        
    }

    public function getTablePK($table){
        $fields = $this->describeTable($table);
        //var_dump($fields);
        for($i=0;$i<count($fields);$i++){
            if($fields[$i]["Key"] == "PRI"){
                return $fields[$i]['Field'];
            }
        }
        return "No Pk Found for table ".$table;
    }
    
    public function describeTable($table){
        $req = "DESC ".trim($table)." ";
        $reset = $this->bdd->query("USE ".Session::get("selected_db"));
        $R_Get = $this->bdd->query(trim($req));
        //echo " D* ".$req." D*";
        $donnee = array();
        while ($data = $R_Get->fetch()){
            $donnee[] = array(
                "Field" => $data['Field'],
                "Key" => $data['Key'],
                "Type" => $data["Type"]
            );
        }        
        return $donnee;
    }

    private function TMP($tables){
        $tmp = "";        
        //var_dump($tables);
        //echo "*** ".count($tables)." **** ";
        for($t=0;$t<count($tables);$t++){
            $description = "";
            if(strlen(trim($tables[$t])) > 0){
                $description = $this->describeTable($tables[$t]);
                //$description = array_unique($description);
                for($d=0;$d<count($description);$d++){
                    $f = $description[$d]["Field"];
                    
                    $tmp .= "\n\t\t\t\t\t'".$f."' => \$data['$f']";                    
                    if($d < count($description)-1){ $tmp .= ","; }
                }                
                $tmp .= ",";                
            }
        }
        //echo " ==> ".$tmp;
        return $tmp;
    }
    
    private function returnGetInfosQuery($table){
        $pk = $this->getTablePK($table);
        $to_array = array($table);
        $r = "\t\$get = \"SELECT * FROM ".$table." WHERE $pk = '\".\$id.\"'\";";
        $r .= "\n\t\t\t\$R_Get = \$this->bdd->query(\$get);\n";
        
        $r .= "\t\t\t\tif(\$R_Get->rowCount() <= 0){";
        $r .= "\n\t\t\t\t\treturn array('status'=>FALSE,'message'=>'Sorry no item with Id = '.\$id.' found in your database');";
        $r .= "\n\t\t\t\t} else{";
        $r .= "\n\t\t\t\t\twhile(\$data = \$R_Get->fetch()) {";
        $r .= "\n\t\t\t\t\t\$donnee = array(\t".$this->TMP($to_array).");";
        $r .= "\n\t\t\t\t\t}";
        $r .= "\n\t\t\t\t\treturn array('status'=>TRUE,'message' => \$donnee);";
        $r .= "\n\t\t\t\t}";
        
        return $r;
    }
    
    private function returnRetriveDataQuery($table,$pk){
        $pk = $this->getTablePK($table);
        $to_array = array($table);
        $r = "\t\$get = \"SELECT $pk FROM ".$table."\";";
        $r .= "\n\t\t\t\$R_Get = \$this->bdd->query(\$get);\n";
        
        $r .= "\t\t\t\tif(\$R_Get->rowCount() <= 0){";
        $r .= "\n\t\t\t\t\treturn array('status'=>FALSE,'message'=>'Sorry no such item found in your database');";
        $r .= "\n\t\t\t\t} else{";
        $r .= "\n\t\t\t\t\twhile(\$data = \$R_Get->fetch()) {";
        $r .= "\n\t\t\t\t\t\$donnee[] = \$data['".$pk."'];";
        $r .= "\n\t\t\t\t\t}";
        $r .= "\n\t\t\t\t\treturn array('status'=>TRUE,'message' => \$donnee);";
        $r .= "\n\t\t\t\t}";
        
        return $r;
    }

    private function returnListeQuery($table){
        //print_r($this->getForeingTables($table));
        $foreignInfos = $this->getForeingTables($table);
        $f_tables = ",";
        $f_keys = "";
        $condi = "\t\t\t\t\t  WHERE ";
        
        for($f=0;$f<count($foreignInfos);$f++){
            $f_tables .= $foreignInfos[$f]["Foreign_table"];
            $f_keys .= $foreignInfos[$f]["Foreign_key"];
            $condi .= $table.".".$foreignInfos[$f]["Column_Name"]." = ".$foreignInfos[$f]["Foreign_table"].".".$foreignInfos[$f]["Foreign_key"]."";
            if($f <  count($foreignInfos)-1){ $f_keys .= ","; $f_tables .= ","; $condi .= "\n\t\t\t\t\t  AND "; }
        }
        
        $r = "\$liste = \"SELECT * FROM $table ".rtrim($f_tables,",")." ";
        
        $limit = " LIMIT \" . \$start . \" , \" . \$limit . \"";
        
        if(count($foreignInfos) > 0){
            $r .= "\n$condi $limit\";";
        }else{
            $r .= $limit."\";";
            //$r = str_replace(",", "", $r);
        }
        
        
        
        $fields = array();
        $tables[] = $table;
        $t_tables = explode(",", $f_tables);
        
        if(count($t_tables) > 0){
            for($t=0;$t<count($t_tables);$t++){
                $tables[] = $t_tables[$t];
            }
        }        

        $r .= "\n\n\t\t\t\$R_List = \$this->bdd->query(\$liste);\n";
        $r .= "\n\t\t\t\$donnee = array();";
        $r .= "\n\t\t\twhile(\$data = \$R_List->fetch()){";
        $r .= "\n\t\t\t\t\$donnee[] = array("; 
        $r .= $this->TMP($tables);
        $r .= "\n\t\t\t\t);";
        $r .= "\n\n\t\t\t}\n\t\t\treturn \$donnee;";
        //echo $r;
        return $r;
    }

    private function returnGetData(){
        $r = "\$get = \$this->retriveData(\$table,\$pk,\$selectedValue);";
        $r .= "\n\t\t\t\$options = \"<option value='-1'>-------------------</option>\";";
        
        $r .= "\n\t\t\tfor(\$i=0;\$i<count(\$get['message']);\$i++){";
            $r .= "\n\t\t\t\tif(\$get['message'][\$i] == \$selectedValue){";
                $r .= "\n\t\t\t\t\t\$options .= \"<option value='\".\$get['message'][\$i].\"' selected='selected'>\".\$get['message'][\$i].\"</option>\";";
            $r .= "\n\t\t\t\t}else{";
                $r .= "\n\t\t\t\t\t\$options .= \"<option value='\".\$get['message'][\$i].\"'>\".\$get['message'][\$i].\"</option>\";";
            $r .= "\n\t\t\t\t}";
        $r .="\n\t\t\t}";
        $r .= "return \$options;";
        return $r;
    }

    private function returnDataNumber($table){
        $r = "\$req = 'SELECT COUNT(*) AS X FROM $table';";
        $r .= "\n\t\t\t\$R_Get = \$this->bdd->query(\$req);";
        $r .= "\n\t\t\t\$nb = \$R_Get->fetch();";
        $r .= "\n\t\t\t\$nb = \$nb['X'];";
        $r .= "\n\t\t\treturn \$nb;";
        
        return $r;
    }


    public function buildModel($modeName,$path,$isCrud){
        $file = ucfirst($modeName)."Model.php"; 
        echo "Creating file $file...";
        if(!file_exists($path.$file)){
            touch($path.$file);            
        }
        echo " ok\n";
        echo "Opening file $file ...";
        $fp = fopen($path.$file, "w");
        echo " ok\n";
        
        echo "\tWriting file content ... ";
        $fields = $this->getTableInfos($modeName);
        //print_r($fields);
        $crudContent = "
        public function add(".ucfirst($modeName)."Controller \$args){
            ".$this->returnAddQuery(($modeName), $fields)."
        }

        public function edit(".ucfirst($modeName)."Controller \$args){
            ".$this->returnEditQuery(($modeName), $fields)."
        }

        public function delete(\$id){
            ".$this->returnDeleteQuery(($modeName), $fields)."
        }
        
        public function listAll(\$start = 0, \$limit = 10){
            ".($this->returnListeQuery($modeName))."
        }
        
        public function getInfos(\$id){
            ".($this->returnGetInfosQuery($modeName))."
        }
        
        public function retriveData(\$table,\$pk){
            ".($this->returnRetriveDataQuery($modeName,$key))."
        }
        
        public function getData(\$table,\$pk,\$selectedValue = -1){
            ".($this->returnGetData())."
        }
        
        public function getTotalNumber(){
            ".($this->returnDataNumber($modeName))."
        }
                ";
        
        $crud = ($isCrud == TRUE)?$crudContent:"";
        $extend = ($isCrud == TRUE)?"extends Model":"";
        
        $content = "
<?php
    class ".ucfirst($modeName)."Model $extend{
    ";
          
     $content .= "
         \n\t\tpublic function __construct(){
            //todo stuff here :) 
            parent::__construct();
        }
        
        $crud
        
    }
    ";
        fputs($fp, trim($content));
        fclose($fp);
        echo " ok\n";        
    }
    
    public function retriveData($table,$pk,$selectedValue){
        $req = "SELECT $pk FROM $table";
        $R_Get = $this->bdd->query($req);
        
        $donnee = array();
        while ($data = $R_Get->fetch()){
            $donnee[] = $data[$pk];
        }
        return $donnee;
    }
    
    public function getForeingKeyTable($fk,$table){
        $foreigneData = $this->getForeingTables($table);
        for($i=0;$i<count($foreigneData);$i++) {
            if($foreigneData[$i]['Column_Name'] == $fk){
                $ftable = $foreigneData[$i]['Foreign_table'];
            }
        }
        return $ftable;
    }
    
    public function getData($table,$pk,$selectedValue='-1'){
        $data = $this->retriveData($table,$pk,$selectedValue);
        $options = "<option value='-1'>-------------------------------</option>";
        
        for($i=0;$i<count($data);$i++){
            if($data[$i] == $selectedValue){
                $options .= "\n\t\t\t\t<option value='".$data[$i]."' selected='selected'>".$data[$i]."</option>";
            }else{
                $options .= "\n\t\t\t\t<option value='".$data[$i]."'>".$data[$i]."</option>";
            }
        }
        return $options;
    }    
}