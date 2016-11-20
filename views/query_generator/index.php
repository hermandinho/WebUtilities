<div style="width: 80%;float: right">
    <center class="tile-area-grayed" style="width: 98%"><h1><strong><i class="icon-code fg-white"></i> Query Generator</strong></h1></center>
    <?php
        $test = Session::get("selected_db");
        if (!isset($test)) {
            echo "<div class='tile-area-darkRed'><h1>Please select a data base <a href='http://" . URL . "DBManager'>Here</a> </h1></div>";
        } else {
            $tables = $this->DBFields;
            //var_dump($this->TableFields);
            
    ?>
    <form method="post" action="QueryGenerator/makeQuery" class="query_builder_form" target="_blank">
        <div class='input-control select info-state' data-role='input-control'>
            <select name='query' class="query_type_select" style='width: 300px'>
                <option value="-1">Select Qyery Type </option>
                <option value="SELECT">Select</option>
                <option value="UPDATE">Update</option>
                <option value="DELETE">Delete</option>
                <option value="ALTER">Alter</option>
            </select>
        </div>
        <h3 class="tile-area-darkOrange">Sorry Only Select Queries Are Available now :( <i class="icon-sale"></i></h3>

        <table class="table hovered">
            <thead>
                <th>Tables</th>
                <th>Columns</th>
                <th>Where</th>
                <th>Conditions</th>
                <th>Values</th>
                <th>Links</th>
            </thead>   
            <tbody id="here">
                <tr>                
                    <td>
                        <div class='input-control select info-state' data-role='input-control'>
                            <select name='table[]' class='table_select' style='width: 150px'>
                                <option value="-1">Select a table</option>
                                <?php
                                    for($i=0;$i<count($tables);$i++){
                                        echo '<option value='.$tables[$i].'>'.$tables[$i].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </td>

                    <td>
                        <div class='input-control select info-state' data-role='input-control'>
                            <select name='columns[table_select_list][]' class='columns_select table_select_list' id='table_select' multiple='multiple' size='5'> 
                            </select>
                        </div>
                    </td>

                    <td>
                        <div class='input-control select info-state' data-role='input-control'>
                            <select name='where[table_select_list][]' class='columns_select table_select_list' id='where_select'>                            
                            </select>
                        </div>
                    </td> 

                    <td>
                        <div class='input-control select info-state' data-role='input-control'>
                            <select name='condition[table_select_list][]' class='condition1_select'>
                                <option value='-1'>Select Condition</option>
                                <option value='='>=</option>
                                <option value='<'><</option>
                                <option value='>'>></option>
                                <option value='>='>>=</option>
                                <option value='<='><=</option>
                                <option value='!='>!=</option>
                            </select>
                        </div>
                    </td>

                    <td>
                        <div class='input-control text' data-role='input-control'>
                            <input type='text' value='' name='values[table_select_list][]' data-state='success'>
                        </div>                    
                    </td>


                    <td>
                        <div class='input-control select info-state' data-role='input-control'>
                            <select name='links[table_select_list][]' class='condition1_select'>
                                <option value='-1'>Select a linking command</option>
                                <option value='AND'>And</option>
                                <option value='OR'>Or</option>
                            </select>
                        </div>
                    </td>                
                </tr>            
            </tbody>
        </table>
        <button id="generateId" class="primary"><i class="icon-newspaper"></i> Add new Raw </button>
        <span class="divider"></span>
        <button id="generateId" type="submit" class="primary"><i class="icon-wrench"></i> Make Query</button>
    </form>
    <?php
       }
    ?>

    <script>

        
        $(document).ready(function(){
            $("#generateId").click(function(e){
                e.preventDefault();
               //$("#here").append("<tr><td><div class='input-control select info-state' data-role='input-control'><select name='table[]' class='table_select'><option value='-1'>Select a table</option><?php for($i=0;$i<count($tables);$i++){echo '<option value='.$tables[$i].'>'.$tables[$i].'</option>';}?></select></div></td><td></td></tr>");
                $.post(
                        "QueryGenerator/addNewRaw",
                        {},
                        function(data){
                            $("#here").append(data);
                        }
                    );
            });
            
            $(".query_builder_form").on("submit",function(e){
                //e.preventDefault();
                $.post(
                      $(this).attr("action"),
                      {
                          data:$(this).serializeArray()
                      },
                      function(data){
                          console.log(data);
                      }
                    );
            });
        });
        
        $(document).on("change",".table_select",function(){
            //alert($(this).next(".columns_select").text("ssssssssss"));
            var x = $(this);
            
            //x.attr("name","[][]");
            
            if($(this).val() <= 0){
                alert("Please select a Table !!! ");
                var Id = x.attr('class').split(" ");
                //alert(Id[0]);
                $("."+Id[0]+"_list").html("");                
            }else{
                $.post(
                        "QueryGenerator/getTableColumns",
                        {
                            Table: $(this).val()
                        },
                        function(data){
                            var Id = x.attr('class').split(" ");
                            $("."+Id[0]+"_list").html(data);
                            
                        }                        
                    );
            }
        });
        
        jQuery.fn.nextMatching = function(selector){
            return $("~ "+selector,this).first();
        };
    </script>