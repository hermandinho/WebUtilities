<div style="width: 80%;float: right">
    <center class="tile-area-grayed" style="width: 98%"><h1><strong><i class="icon-database fg-white"></i> Data Base manager</strong></h1></center>
    <?php 
        $liste = $this->dataList;        
        
        if(isset($_GET['use'])){
            extract($_GET);
            if(in_array($use, $liste)){
                Session::set("selected_db", $use);
            }  else {
                echo "<div class='tile-area-darkRed'><h1>Error !!!!! Unknown Database <a href='http://".URL."DBManager'>Back</a> </h1></div>";
                die();
            }
        }
    ?>
    
    <table class="table hovered" style="text-align: center;font-family: cursive;">
        <thead class="bordered">
            <th>#</th>
            <th>DB Name</th>
            <th>Actions</th>
        </thead>
        <tbody>
            <?php 
                if(count($liste) <= 0){
                    echo "<tr><td colspan='3'>No Database found :( </td></tr>";
                }else{
                    $class = "";
                    for($i=0;$i<count($liste);$i++){
                        if($liste[$i] == Session::get("selected_db")){
                            $class = "bold";
                        }else{
                            $class = "";
                        }
                        echo "<tr class='".$class."'><td>".($i+1)."</td>";
                        echo "<td>".$liste[$i]."</td>";
                        echo "<td>
                                <a href='?use=".$liste[$i]."' class='image-button bg-darkGreen fg-white image-left'>
                                    use <i class='icon-lightning bg-green fg-white'></i>
                                </a>
                                    <em class='element-divider'></em>
                                <a href='?view=".$liste[$i]."' class='image-button bg-darkBlue fg-white image-left'>
                                    view <i class='icon-eye bg-blue fg-white'></i>
                                </a>                                
                              </td> </tr>";
                    }
                }
            ?>
        </tbody>
    </table>
</div>