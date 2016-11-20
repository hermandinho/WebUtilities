
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormBuilder
 *
 * @author Manho
 */
class FormBuilderController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function listeData($table,$path){
        $this->loadModel("ClassManager");
        
        $tab_fields = $this->model->describeTable($table);
        $thead = "";
        $liste = "";        
        $pk = $this->model->getTablePK($table);
        $actions = "";
        
        for($i=0;$i<count($tab_fields);$i++){
            $thead .= "<th>".$tab_fields[$i]['Field']."</th>";
            $liste .= "\t\t\t\t\t\t\t\techo \"<td>\".\$data[\$i]['".$tab_fields[$i]['Field']."'].\"</td> \";\n";
            $actions .= "\t\t\t\t\t\t\t\techo 'Ok';";
        }
        $thead .= "<th>Action</th>";
        
        $fp = fopen($path."/display.php", "w");
        
        $content = "
<?php     
    //require_once 'models/".ucfirst($table)."Model.php';
    \$objModel = new ".ucfirst($table)."Model();
    \$page = \$this->page;
    \$totalData = \$this->totalData;
    \$paginator = new Paginator(DISPLAY_PER_PAGE, \$totalData);
    \$paginator->setCurrent(\$page);
    \$pageNumber = isset(\$page) ? (\$page) : '1';
    \$limitStart = abs((\$paginator->getCurrentPage() - 1) * DISPLAY_PER_PAGE);
    \$data = \$objModel->listAll(\$limitStart,DISPLAY_PER_PAGE); 
?>            
<div class='content' style='width: 80%;float: right'>
    <h2>Liste de <u>".ucfirst($table)."</u></h2>
    
    <table class='hovered table'>
        <thead>
            $thead
        </thead>
        <tbody>
            <?php
            \tif(count(\$data) <= 0){
                \techo 'No available data to display';
            \t}else{
                \tfor(\$i=0;\$i<count(\$data);\$i++){
                    \techo '<tr>';
$liste    
    \t\t\t\t\t\t\techo \"<td><a href='\".URL.'".ucfirst($table)."/edit/'.\$data[\$i]['".$pk."'].\"'><i class='icon-pencil'></i> Edit</a> <a href='\".URL.'".ucfirst($table)."/delete/'.\$data[\$i]['".$pk."'].\"'><i class='icon-basket'></i> Delete</a> <!--<a href='\".URL.'".ucfirst($table)."/view/'.\$data[\$i]['".$pk."'].\"'><i class='icon-eye'></i> View</a> --> </td>\";
                    \techo '</tr>';
                \t}
            \t}
            ?>
        </tbody>
    </table>
    ".$this->buildNavigation(ucfirst($table))."
        <br><br><hr>
    <center><?php echo \$paginator->buildPaginator('&raquo;', 'Page','".ucfirst($table)."/display/'); ?> </center>
</div>
                ";
        fputs($fp, $content);
        fclose($fp);
    }
    
    public function buildAddForm($table){
        $this->loadModel("ClassManager");        
        $tab_fields = $this->model->describeTable($table);                
        $pk = $this->model->getTablePK($table);
        
        $form = "<div class='content' style='width: 80%;float: right'>";
        $form .= "\t<form name='add_form' method='post' action='<?php echo URL; ?>".ucfirst($table)."/add'>";
        $form .= "\n\t\t<table class='table'>";
        $form .= "\n\t\t\t<caption>Add data from <u>".ucfirst($table)."</u></caption>";
        
        for($i=0;$i<count($tab_fields);$i++){
            if($tab_fields[$i]['Key'] === "PRI"){
                /*$options = "required='required' disabled='disabled'";
                $form .= "\n\t\t\t<?php echo \"<tr> <td><label for='fake_".$tab_fields[$i]["Field"]."'>".ucfirst($tab_fields[$i]["Field"])."</label></td>  <td>".$this->form_text("fake_".$tab_fields[$i]["Field"],"",$options)."</td></tr>\"; ?>";
                */
            }elseif ($tab_fields[$i]['Key'] === "MUL") {
                $form .= "\n\t\t\t<?php ";  
                //$form .= "\$data['message']['".($tab_fields[$i]['Field'])."']";
                $form .= "\n\t\t\t\trequire_once('models/".ucfirst($this->getForeingKeyTable($tab_fields[$i]['Field'], $table))."Model.php');  \$model = new ".ucfirst($this->getForeingKeyTable($tab_fields[$i]['Field'],$table))."Model(); ";
                //$s = "\$data['message']['".($tab_fields[$i]['Field'])."']";
                $form .= "\n\t\t\t\t\$donnee = \$model->getData('".$tab_fields[$i]['Field']."','".$this->getForeingKeyTable($tab_fields[$i]['Field'], $table)."');";
                //$str .= "\n\t\t\tvar_dump(\$donnee); ";
                $form .= "\n\t\t\t\techo \"<tr> <td><label for='".$tab_fields[$i]["Field"]."'>".$tab_fields[$i]["Field"]."</label></td> <td> <select name='".$tab_fields[$i]['Field']."' id='".$tab_fields[$i]['Field']."' required='required'>\".\$donnee.\"</select> </td></tr>\";";
                $form .= "\n\t\t\t?> ";
            }else{
                $type = $tab_fields[$i]['Type'];
                $type = explode("(", $type);
                if($type[0] == "tinyint"){
                    $form .= "\n\t\t\t<?php echo \"<tr> <td><label for='".$tab_fields[$i]["Field"]."'>".ucfirst($tab_fields[$i]["Field"])."</label></td>  <td>".$this->form_CheckBox($tab_fields[$i]["Field"])."</td></tr>\"; ?>";
                }else{
                    $form .= "\n\t\t\t<?php echo \"<tr> <td><label for='".$tab_fields[$i]["Field"]."'>".ucfirst($tab_fields[$i]["Field"])."</label></td>  <td>".$this->form_text($tab_fields[$i]["Field"])."</td></tr>\"; ?>";
                }                
            }
        }        
        $form .= "<tr><td colspan='2'><button class='command-button primary' type='submit'><i class='icon-new'></i> Add</button></td></tr>";
        $form .= "\n\t\t<table>";
        $form .= "\t</form>";
        $form .= "</div>";
        
        return $form;
        //return "Build Add Form here for ".$table." From ";
        //$this->model->helo();
    }
    
    public function buildEditForm($table){
        $this->loadModel("ClassManager");
        
        $tab_fields = $this->model->describeTable($table);
                
        $pk = $this->model->getTablePK($table);
        
        $str = "<?php \$data = \$this->data; if(\$data['status'] == FALSE){ die(\$data['message']); } //var_dump(\$data); ?>";
        $str .= "\n<div class='content metro' style='width: 80%;float: right'>";
        $str .= "\n\t<form name='edit_form' method='post' action='<?php echo URL; ?>".ucfirst($table)."/edit'>";
        
        $str .= "\n\t<table class='table'>";
        $str .= "\n\t\t<caption>Editing data from <u>".ucfirst($table)."</u></caption>";
        $str .= "\n\t\t<tbody>";
        $options = '';
        //var_dump($this->getForeingDataForEdit($table));
        /*$str .= "\n\t\t\t<?php require_once('models/".ucfirst($table)."Model.php');  \$model = new ".ucfirst($table)."Model(); ?>";*/
        for($i=0;$i<count($tab_fields);$i++){
            if($tab_fields[$i]['Key'] === "PRI"){
                $options = "required='required' disabled='disabled'";
                $str .= "\n\t\t\t<?php echo \"<tr> <td><label for='fake_".$tab_fields[$i]["Field"]."'>".ucfirst($tab_fields[$i]["Field"])."</label></td>  <td>".$this->form_text("fake_".$tab_fields[$i]["Field"],"\".\$data['message']['".($tab_fields[$i]['Field'])."'].\"",$options)."</td></tr>\"; ?>";
                $str .= "\n\t\t\t<?php echo \"<tr> <td>".$this->form_hidden($tab_fields[$i]["Field"],"\".\$data['message']['".($tab_fields[$i]['Field'])."'].\"")."</td></tr>\"; ?>";
            }elseif ($tab_fields[$i]['Key'] === "MUL") {
                $str .= "\n\t\t\t<?php ";  
                $s = "\$data['message']['".($tab_fields[$i]['Field'])."']";
                $str .= "\n\t\t\t\trequire_once('models/".ucfirst($this->getForeingKeyTable($tab_fields[$i]['Field'], $table))."Model.php');  \$model = new ".ucfirst($this->getForeingKeyTable($tab_fields[$i]['Field'],$table))."Model(); ";
                
                $str .= "\n\t\t\t\t\$donnee = \$model->getData('".$tab_fields[$i]['Field']."','".$this->getForeingKeyTable($tab_fields[$i]['Field'], $table)."',$s);";
                //$str .= "\n\t\t\tvar_dump(\$donnee); ";
                $str .= "\n\t\t\t\techo \"<tr> <td><label for='".$tab_fields[$i]["Field"]."'>".$tab_fields[$i]["Field"]."</label></td> <td> <select name='".$tab_fields[$i]['Field']."' id='".$tab_fields[$i]['Field']."' required='required'>\".\$donnee.\"</select> </td></tr>\";";
                $str .= "\n\t\t\t?> ";
            }else{
                $str .= "\n\t\t\t<?php echo \"<tr> <td><label for='".$tab_fields[$i]["Field"]."'>".ucfirst($tab_fields[$i]["Field"])."</label></td>  <td>".$this->form_text($tab_fields[$i]["Field"],"\".\$data['message']['".($tab_fields[$i]['Field'])."'].\"")."</td></tr>\"; ?>";
            }
        }
        $str .= "<tr><td colspan='2'><button class='command-button primary' type='submit'><i class='icon-pencil'></i> Update</button></td></tr>";
        $str .= "\n\t\t</tbody>";
        $str .= "\n\t</table>";
        
        $str .= "\n\t</form>";
        $str .= "\n</div>";
        
        
        return $str;
    }
    
    public function buildDeleteForm($table){
        return "<?php \n\t\$data = \$this->message; //var_dump(\$this->message); 
                    \n\techo \$data['message'];
                    \n\t\techo \"<a href=\".URL.\"".ucfirst($table).">Back</a>\";
                ?>";
    }
    
    private function form_text($name,$value='',$options=''){
        return "<input type='text' value='$value' name='$name' id='$name' class='form-control' $options/>";
    }
    
    private function form_hidden($name,$value='',$options=''){
        return "<input type='hidden' value='$value' name='$name' id='$name' class='form-control' $options/>";
    }
    
    private function form_CheckBox($name,$value='',$options=''){
        return "<input type='checkbox' value='$value' name='$name' id='$name' class='form-control' $options/>";
    }
    
    private function form_select($name,$options,$selected){
        $str = "<select name='$name'>";
        $str .= $options;
        $str .= "</select>";
    }

    private function buildNavigation($table){
        $str = "<?php echo \"<hr><a class='command-button primary' href='\".URL.\"".ucfirst($table)."/add'><i class='icon-new'></i> New Item </a>\";";
        //$str .= " <a class='command-button primary' href='".URL."".ucfirst($table)."/add'><i class='icon-new'></i> </a>";
        
        $str .= " ?>";
        
        //echo $str;
        return $str;
    }
    
    private function getForeingKeyTable($fk,$table){
        $foreigneData = $this->model->getForeingTables($table);
        for($i=0;$i<count($foreigneData);$i++) {
            if($foreigneData[$i]['Column_Name'] == $fk){
                $ftable = $foreigneData[$i]['Foreign_table'];
            }
        }
        //echo $ftable;
        return $ftable;
    }
    
    private function getData($table,$pk,$selectedValue='-1'){
        $data = $this->model->retriveData($table,$pk,$selectedValue);
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
