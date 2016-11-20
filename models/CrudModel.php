<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CrudManager
 *
 * @author Manho
 */
class CrudModel extends Model {
    //put your code here
    /*USE information_schema;
        select table_name,column_NAME,CONSTRAINT_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
        from KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = 'demodb'
        AND TABLE_NAME = 't3' 
        and REFERENCED_COLUMN_NAME is not null */
    
    public function buildCrud($class){
        echo "\nFor ".$class;
    }
    
    public function writeIndexController($path1,$pathView){
        $fp = fopen($path1."/IndexController.php", "w");
        $content = "
<?php
    
    class IndexController extends Controller{
        \n\t\tpublic function index(){
            \$this->view->render('index/index');
        \n\t\t}
    }
                ";
        fputs($fp, $content);
        fclose($fp);
        
        if(!is_dir($pathView."/index")){
            mkdir($pathView."/index");
        }
        
        $fp = fopen($pathView."/index/index.php", "w");
        fputs($fp, "<h1>Welcome to your Home Page</h1>");
        fclose($fp);
    }

    public function createProjectStructure($name){
        $this->makeProjectStructure($name);
    }
    
    private function writeHtaccess($path){
        if(!is_dir($path)){
            echo "Cannot write .htaccess file due to invalid path ";
            return false; 
        }
        echo "    Writing $path.htaccess";
        $fp = fopen($path."/.htaccess","w");
        $content = "
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]                
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }
    
    private function writeConfig($path,$projectName){
        if(!is_dir($path)){
            echo "Cannot write config files due to invalid path";
            return false;
        }
        
        echo "\n    Writing $path/config.php";
        
        $fp = fopen($path."/config.php","w");
        $content = "
<?php
\tdefine('URL','http://localhost/".$projectName."/');
\tdefine('DB_TYPE','mysql');
\tdefine('DB_HOST','localhost');
\tdefine('DB_NAME','".Session::get("selected_db")."');
\tdefine('DB_USER','root');
\tdefine('DB_PASS','');
\tdefine('DISPLAY_PER_PAGE','2'); // Nombre de données de la pagination
                ";
        
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;
    }
    
    public function writeAllLibs($path){
        if(!file_exists($path."/Bootstrap.php")){ $this->writeBootstrap($path); }
        if(!file_exists($path."/Controller.php")){ $this->writeController($path); }
        if(!file_exists($path."/DataBase.php")){ $this->writeDataBase($path); }
        if(!file_exists($path."/Model.php")){ $this->writeModel($path); }
        if(!file_exists($path."/Session.php")){ $this->writeSession($path); }
        if(!file_exists($path."/View.php")){ $this->writeView($path); }
    }
 
    private function writePublic($src,$des){
        $dir = opendir($src);
                
        while (false != ($file = readdir($dir))){
            if(($file != '.') && ($file != '..') && ($file != "images")){
                if(is_dir($src.'/'.$file)){
                    $this->writePublic($src.'/'.$file, $des.'/'.$file);
                }else{
                    copy($src.'/'.$file, $des.'/'.$file);
                }
            }
        }
        closedir($dir);
        /*shell_exec("cp -r $src $des");
        echo "Purrr ";*/
    }

    private function writeView($path){
        if(!is_dir($path)){
            echo "Can not write View file due to invalid path !!!";
            return false;
        }
        
        echo "   writing $path./View.php";
        
        $fp = fopen($path."/View.php","w");
        
        $content = "
<?php
    /*
    * Base View class By Mermanho :) 
    */
    class View{
        function __construct() {
            //echo 'this is a view <br>';
        }
        
        public function render(\$name,\$noinclude = FALSE){
            if(\$noinclude == TRUE){
                require 'views/'.\$name.'.php';
            }  else {
                require 'views/header.php';
                require 'views/navLeft.php';
                require 'views/'.\$name.'.php';
                require 'views/footer.php';
            }
        }
        
        public function adminRender(\$name,\$noinclude = FALSE){ // Use this to set up admin pages :)
            if(\$noinclude == TRUE){
                require 'views/'.\$name.'.php';
            }  else {
                require 'views/header_admin.php';
                require 'views/'.\$name.'.php';
                require 'views/footer.php';                
            }
        }
    }               
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }      
    
    private function writeSession($path){
        if(!is_dir($path)){
            echo "Can not write Session file due to invalid path !!!";
            return false;
        }
        
        echo "    Writing $path/Session.php";
        $fp = fopen($path."/Session.php","w");
        
        $content = "
<?php
    /*
    * Static session class By Mermanho :)    *
    */
class Session{
    public static function init(){
        session_start();
    }
    
    public static function set(\$key,\$val){
        \$_SESSION[\$key] = \$val;
    }
    
    public static function get(\$key){
        if(isset(\$_SESSION[\$key]))
            return \$_SESSION[\$key];
    }
    
    public static function destroy(){
        unset(\$_SESSION);
        session_destroy();
    }
}               
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }
 
    private function writeModel($path){
        if(!is_dir($path)){
            echo "Can not write Model file due to invalid path !!!";
            return false;
        }
        
        echo "    Writing $path/Model.php";
        
        $fp = fopen($path."/Model.php","w");
        
        $content = "
<?php
    /*
    * Base Mode class By Mermanho :)
    * All models shoul extend this class
    */
    class Model{
        function __construct() {
            //echo 'Welcome  to the Model Base Class';
            \$this->bdd = new DataBase();\n
            \$this->bdd->exec('SET CHARACTER SET utf8');
        }
    }               
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }     
    
    private function writeDataBase($path){
        if(!is_dir($path)){
            echo "Can not write DataBase file due to invalid path !!!";
            return false;
        }
        
        echo "    Writing $path/DataBase.php";
        $fp = fopen($path."/DataBase.php","w");
        
        $content = "
<?php
    /*
    * Data Base By Mermanho :)
    */
class DataBase extends PDO {

    public function __construct() {
        parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASS);
    }

}                
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }    
    
    private function writeController($path){
        if(!is_dir($path)){
            echo "Can not write Controller file due to invalid path !!!";
            return false;
        }
        
        echo "    Writing $path/Controller.php";
        $fp = fopen($path."/Controller.php","w");
        
        $content = "
<?php
    /*
    * Base Controller By Mermanho :)
    * All controllers should extend this base controller
    */
    class Controller{
        function __construct(\$data = '') {
            //echo 'Main Controller <br>';
            \$this->view = new View();
            if(is_array(\$data)){ \$this->hydrater(\$data); }
                
        }
        
        public function loadModel(\$name){
            \$path = 'models/'.ucfirst(\$name).'Model.php';
            if(file_exists(\$path)){
                require \$path;
                \$modelName = ucfirst(\$name).'Model';
                \$this->model = new \$modelName();
            }
        }
        
        public function hydrater(array \$data){
            foreach(\$data as \$key => \$value){
                \$method = 'set'.ucfirst(\$key);
                \n\t\t\t\tif(method_exists(\$this,\$method)){
                    \$this->\$method(\$value);
                }
            }
        }
    }                
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }

    private function writeBootstrap($path){
        if(!is_dir($path)){
            echo "Cannot write config files due to invalid path";
            return false;
        }
        
        echo "    Writing $path/Bootstrap.php";
        $fp = fopen($path."/Bootstrap.php","w");
        $content = "
<?php
/*
*Bootstrap by Hermanho :)
*
*/
class Bootstrap {

    function __construct() {
        \$l = isset(\$_GET['url']) ? \$_GET['url'] : null;
        \$url = rtrim(\$l, '/');
        \$url = rtrim(\$url, \"'\");        
        Session::init();
        \$url = explode('/', \$url);

        \$file = 'controllers/' . ucfirst(\$url[0]) . 'Controller.php'; //Chemin du controlleur

        if (empty(\$url[0])) {
            require_once 'controllers/IndexController.php';
            \$controller = new IndexController();
            \$controller->index();
            return FALSE;
        }

        if (file_exists(\$file)) {
            require \$file;
        } else {
            //throw new Exception('Le Controller <strong>'.ucfirst(\$url[0]).'Controller.php</strong> est introuvable');
            require_once 'controllers/ErrorController.php';
            \$error = new ErrorController('no_controller');
            return FALSE;
        }

        \$ctrl = ucfirst(\$url[0]) . 'Controller';
        \$controller = new \$ctrl;
        \$controller->loadModel(\$url[0]);

        if (isset(\$url[2])) {
            \$param = \$url[2];
            \$param = rtrim(\$param, '--');
            \$param = rtrim(\$param, \"'\");
            \$controller->{\$url[1]}(\$param);
            return FALSE;
        } else {
            if (isset(\$url[1])) {
                if (method_exists(\$controller, \$url[1])) {
                    \$controller->{\$url[1]}();
                } else {
                    require 'controllers/ErrorController.php';
                    \$error = new ErrorController('no_method');
                }

                return FALSE;
            }
        }
        \$controller->index();
    }

}
                
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;        
    }
    
    private function writeIndex($path){
        if(!is_dir($path)){
            echo "Can not write Index file due to invalid path !!!";
            return false;
        }
        
        echo "    Writing $path index.php";
        $fp = fopen($path."/index.php","w");
        
        $content = "
<?php
    /*
    * Main Index By Mermanho :)
    */
    include './config/config.php';    
    require './libs/Controller.php';
    require './libs/DataBase.php';
    require './libs/Model.php';
    require './libs/Session.php';
    require './libs/View.php';
    require './libs/Bootstrap.php';
    require './libs/Paginator.php';
    
    
    \$app = new Bootstrap();
                
                ";
        fputs($fp, $content);
        fclose($fp);
        echo " ...  done\n";
        return TRUE;         
    }
    
    private function getDBTables(){
        $this->bdd->query("USE ".Session::get("selected_db"));
        $req = "SHOW TABLES";
        $R_Get = $this->bdd->query($req);
        //echo " ***************************** ".$R_Get->rowCount();
        $donnee = array();
        while ($data = $R_Get->fetch()){
            $donnee[] = array( "Table" => $data["Tables_in_".Session::get("selected_db")]);
        }
        //var_dump($donnee);
        return $donnee;
    }
    
    private function buildViewStructure($path){
        $tables  = $this->getDBTables();
        require 'controllers/FormBuilderController.php';
        $f = new FormBuilderController();
        //$f->loadModel("FormBuilder");
        //var_dump($tables);
        for($i=0;$i<count($tables);$i++){
            if(!is_dir($path."/".$tables[$i]["Table"])){
                $dir = $path."/".$tables[$i]["Table"];
                mkdir($dir);
                
                $addF = $dir."/add.php";
                $fp = fopen($addF, "w");
                    fputs($fp, $f->buildAddForm($tables[$i]["Table"]));
                    fclose($fp);
                    
                $editF = $dir."/edit.php";
                $fp = fopen($editF, "w");
                    fputs($fp, $f->buildEditForm($tables[$i]["Table"]));
                    fclose($fp);
                    
                $deleteF = $dir."/delete.php";
                $fp = fopen($deleteF, "w");
                    fputs($fp, $f->buildDeleteForm($tables[$i]["Table"]));
                    fclose($fp);                
                
                $displayF = $dir."/display.php";
                $fp = fopen($displayF, "w");
                    fputs($fp, $f->listeData($tables[$i]["Table"],$dir));
                    fclose($fp);
            }
        }
        
    }

    private function makeProjectStructure($name){
        $path = GENERATED_CRUD;
        if(!is_dir($path.$name)){
            mkdir($path.$name);
        }        
        $root = $path.$name."/";
        
        if(!is_dir($root."config")){ mkdir($root."config"); }
        $configDir = $root."config";
        
        if(!is_dir($root."libs")){ mkdir($root."libs"); }
        $libsDir = $root."libs";
        
        if(!is_dir($root."models")){ mkdir($root."models"); }
        $modelsDir = $root."models";
        
        if(!is_dir($root."controllers")){ mkdir($root."controllers"); }
        $controllersDir = $root."controllers";
        
        if(!is_dir($root."public")){ mkdir($root."public"); }
        $publicDir = $root."public";
        
        if(!is_dir($publicDir."/css")){ mkdir($publicDir."/css"); }
        $publicCSS = $publicDir."/css";
        
        if(!is_dir($publicDir."/images")){ mkdir($publicDir."/images"); }
        $publicImg = $publicDir."/images";
        
        if(!is_dir($publicCSS."/less")){ mkdir($publicCSS."/less"); }
        $publicLess = $publicCSS."/less";
        
        if(!is_dir($publicDir."/js")){ mkdir($publicDir."/js"); }
        $publicJs = $publicDir."/js";
        
        if(!is_dir($publicJs."/holder")){ mkdir($publicJs."/holder"); }
        $publicHolder = $publicDir."/holder";
        
        if(!is_dir($publicJs."/jquery")){ mkdir($publicJs."/jquery"); }
        $publicJQ = $publicJs."/jquery";
        
        if(!is_dir($publicJs."/metro")){ mkdir($publicJs."/metro"); }
        $publicMetro = $publicJs."/metro";
        
        if(!is_dir($publicJs."/prettify")){ mkdir($publicJs."/prettify"); }
        $publicPrettify = $publicJs."/prettify";
        
        if(!is_dir($publicDir."/data")){ mkdir($publicDir."/data"); }
        $publicData = $publicDir."/data";
        
        if(!is_dir($publicDir."/fonts")){ mkdir($publicDir."/fonts"); }
        $publicData = $publicDir."/fonts";
        
        if(!is_dir($root."views")){ mkdir($root."views"); }
        $viewsDir = $root."views";
        
        if(!file_exists($configDir."/config.php")){ $this->writeConfig($configDir, $name); }
        if(!file_exists($root."/.htaccess")){ $this->writeHtaccess($root); }
        if(!file_exists($root."/index.php")){ $this->writeIndex($root); }
        
        $this->writeAllLibs($libsDir);
        $this->buildViewStructure($viewsDir);
        $this->writePublic(ROOT."public", $publicDir);       
        $this->doDesigne($root."views",ucfirst($name));
        $this->writeIndexController($controllersDir,$viewsDir);
        //$d = opendir($viewsDir);
        
        copy("libs/Paginator.php", $libsDir."/Paginator.php");
        
        echo " ...  done\n";
    }
    
    public function doDesigne($path,$n){
        $this->writeHeaderFile($path,$n);
        $this->writeFooterFile($path);
        $this->writeNavLeft($path,$this->getDBTables());
    }
    
    private function writeHeaderFile($path,$name){
        $fp = fopen($path."/header.php", "w");
        $content = "
<html>    
    <head>
        <title>$name</title>
        <link rel='stylesheet' href='<?php echo URL;?>public/css/metro-bootstrap.css'>
        <link href='<?php echo URL;?>public/css/metro-bootstrap-responsive.css' rel='stylesheet'>
        <link href='<?php echo URL;?>public/css/iconFont.css' rel='stylesheet'>
        <link href='<?php echo URL;?>public/css/docs.css' rel='stylesheet'>  
        
        <script src='<?php echo URL;?>public/js/jquery/jquery.min.js'></script>
        <script src='<?php echo URL;?>public/js/jquery/jquery.widget.min.js'></script>
        <script src='<?php echo URL;?>public/js/metro.min.js'></script>
        <script src='<?php echo URL;?>public/js/functions.js'></script>
    </head>
    <body class='metro'>
                ";
        fputs($fp, $content);
        fclose($fp);
    }
    
    private function writeFooterFile($path){
        $fp = fopen($path."/footer.php", "w");
        $content = "
    <footer style='z-index: -1000'>
            <nav class='navigation-bar fixed-bottom'>
                <h2>&COPY; 2015</h2>
                <span class='page-footer'>Web Generator Tool V1.0 By <em class='alert info right'>Generated by Web Utilities 1.0</em></span>
            </nav>
        </footer>
    </body>
</html>
                ";
        fputs($fp, $content);
        fclose($fp);
    }
    
    private function writeNavLeft($path,$tables){
        $fp = fopen($path."/navLeft.php", "w");
        $menu = '';
        for($i=0;$i<count($tables);$i++){
            $menu .= "\t\t<li class=''><a class='dropdown-toggle' href='#'><i class='icon-forward'></i> ".ucfirst($tables[$i]['Table'])."</a> "
                    . "<ul class='dropdown-menu' data-show='hover' data-role='dropdown'>"
                    . "\n\t\t<li><a href='<?php echo URL; ?>".ucfirst($tables[$i]['Table'])."/add'> Nouveau </a></li>"
                    . "\n\t\t<li><a href='<?php echo URL; ?>".ucfirst($tables[$i]['Table'])."'> Lister </a></li>"
                    . "\n\t</ul>";
        }        
        $content = "<nav class='sidebar' style='width: 20%;float: left'>
            
    <ul>
        <li>
            <a class='' href='<?php echo URL; ?>'>Acceuil</a>
        </li>
        
       $menu
    </ul>
</nav>
                ";
        fputs($fp, $content);
        fclose($fp);
    }
}
