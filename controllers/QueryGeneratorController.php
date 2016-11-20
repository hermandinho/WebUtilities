<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QueryGenerator
 *
 * @author Manho
 */
class QueryGeneratorController extends Controller {

    public function index() {
        $db = Session::get("selected_db");
        if (isset($db)) {
            $data = $this->model->describeDatabase(Session::get("selected_db"));
            //var_dump($data);
            $this->view->DBFields = $data;

            /* get fields of all tables */
            $data_fields = array();
            for ($i = 0; $i < count($data); $i++) {
                $tmp = $this->model->getTableInfos($data[$i]);
                $data_fields[$data[$i]] = $tmp;
            }
            $this->view->TableFields = $data_fields;
        }
        $this->view->render("query_generator/index");
    }

    public function addNewRaw() {
        $data = $this->model->describeDatabase(Session::get("selected_db"));
        $op = "";
        $generated_id = str_shuffle("automaticllyGeneratedId");
        for ($i = 0; $i < count($data); $i++) {
            $op .= '<option value=' . $data[$i] . '>' . $data[$i] . '</option>';
        }
        //$str = "<tr><td><div class='input-control select info-state' data-role='input-control'><select style='width: 150px' name='table[]' class='$generated_id table_select'><option value='-1'>Select a table</option>$op</select></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='columns[]' class='columns_select' id='$generated_id' multiple='multiple' size='5'></select></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='condition1' class='condition1_select'><option value='-1'>Select Condition</option><option value='AND'>And</option><option value='OR'>Or</option><option value='='>=</option><option value='<'><</option><option value='>'>></option><option value='>='>>=</option><option value='<='><=</option><option value='!='>!=</option></select></div></td><td><div class='input-control text' data-role='input-control'><input type='text' value='' name='values[]' data-state='success'></div></td></tr>";
        $str = "<tr><td><div class='input-control select info-state' data-role='input-control'><select name='table[]' class='$generated_id table_select' style='width: 150px'><option value='-1'>Select a table</option>$op</select></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='columns[$generated_id][]' class='columns_select " . $generated_id . "_list' id='$generated_id' multiple='multiple' size='5'></select></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='where[$generated_id][]' class='columns_select " . $generated_id . "_list' id='where_select'></select></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='condition[$generated_id][]' class='condition1_select'><option value='-1'>Select Condition</option><option value='='>=</option><option value='<'><</option><option value='>'>></option><option value='>='>>=</option><option value='<='><=</option><option value='!='>!=</option></select></div></td><td><div class='input-control text' data-role='input-control'><input type='text' value='' name='values[$generated_id][]' data-state='success'></div></td><td><div class='input-control select info-state' data-role='input-control'><select name='links[$generated_id][]' class='condition1_select'><option value='-1'>Select a linking command</option><option value='AND'>And</option><option value='OR'>Or</option></select></div></td></tr>";

        echo $str;
    }

    public function getTableColumns() {
        $tmp = $this->model->getTableInfos($_POST['Table']);
        $options = "<option value='*' selected>*</option>";
        for ($i = 0; $i < count($tmp); $i++) {
            $options .= "<option value='" . $tmp[$i]['Name'] . "'>" . $tmp[$i]['Name'] . "</option>";
        }
        echo $options;
    }

    public function makeQuery() {
        $params = array();
        //parse_str($_POST['data'], $params);
        //print_r($this->getAPosition($_POST['table'], 0)); echo "<br>";
//        print_r($this->getAPosition($_POST['table'], 0));
        echo "<pre>";
        //print_r($_POST);
        echo "</pre>";

        $structuredTable = array();

        for ($i = 0; $i < count($_POST['table']); $i++) {
            $structuredTable[] = array(
                "Table" => $this->getAPosition($_POST['table'], $i),
                "Columns" => $this->getAPosition($_POST['columns'], $i),
                "Where" => $this->getAPosition($_POST['where'], $i),
                "Condition" => $this->getAPosition($_POST['condition'], $i),
                "Values" => $this->getAPosition($_POST['values'], $i),
                "Links" => $this->getAPosition($_POST['links'], $i),
            );
        }

        echo "<pre>";
        //var_dump($structuredTable);
        echo "</pre>";

        /* for($j=0;$j<count($structuredTable);$j++){
          $this->doQuery($structuredTable[$j]);
          } */
        $this->doQuery($structuredTable);
    }

    private function doQuery($data) {
        $select = "SELECT ";
        $from = " FROM ";
        $from_where = " WHERE ";

        for ($j = 0; $j < count($data); $j++) {
            $str = "";
            $tableName = $data[$j]["Table"];
            $columns = $data[$j]["Columns"];
            $where = $data[$j]["Where"];
            $conditions = $data[$j]["Condition"];
            $values = $data[$j]["Values"];
            $links = $data[$j]["Links"];
            //print_r($values);
            //echo "<br>";

            foreach ($tableName as $k => $t) {
                //    echo "<br> ==> <strong>Table</strong> = ".$t ." , <strong>Columns</strong> => ";
                $from .= $t . ",";
                columns: foreach ($columns as $c => $cc) {
                    for ($i = 0; $i < count($cc); $i++) {
                        //echo $cc[$i] .",";
                        if ($cc[$i] == '*') {
                            $select .= $t . "." . $cc[$i] . ",";
                            break;
                        }
                        $select .= $t . "." . $cc[$i] . ",";
                    }
                }
                //echo " <strong>Where</strong> => ";
                Where: foreach ($where as $w => $ww) {
                    for ($i = 0; $i < count($ww); $i++) {
                        //echo $ww[$i] .",";
                        if ($ww[$i] != '*') {
                            $from_where .= $t . "." . $ww[$i] . " = '" . $values[$w][0] . "' " . $links[$w][0] . " ";
                        }
                        //$from_where .= $t.".".$ww[$i]." = ".$values[$w][0]." ".$links[$w][0]." ";
                    }
                }
                //echo " <strong>Condition</strong> => ";
                Condition: foreach ($conditions as $cd => $cdd) {
                    for ($i = 0; $i < count($cdd); $i++) {
                        //echo $cdd[$i] .",";
                    }
                }
                //   echo " <strong>Values</strong> => ";
                Values: foreach ($values as $v => $vv) {
                    for ($i = 0; $i < count($vv); $i++) {
                        //echo $vv[$i] .",";
                    }
                }
            }
        }
        $query = rtrim($select, ",") . rtrim($from, ",") . rtrim($from_where, ",");
        echo "<br>" . $query;
    }

    function getAPosition($array, $pos) {
        $keys = array_keys($array);
        return array($keys[$pos] => $array[$keys[$pos]]);
    }

}
