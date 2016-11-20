<div style="width: 80%;float: right">
    <center class="tile-area-grayed" style="width: 98%"><h1><strong><i class="icon-code fg-white"></i> Script Minifier </strong></h1></center>
    <form action="http://<?php echo URL; ?>ScriptMinifier/minifie" method="post" enctype="multipart/form-data">
        <table class="table hoverred">
            <tr>
                <td><label>Select script to minifie</label></td>
                <td>
                    <div class="input-control text warning-state">
                        <input type="file" name="script" required/>
                    </div>                    
                </td>
            </tr>
            
            <tr>
                <td></td>
                <td><button type="submit" class="bg-darkGreen fg-white large">Minify</button></td>
            </tr>
        </table>
    </form>
    <hr>
    <div class="content">
        <div>
            <?php 
                @$s = Session::get("fileInfos");
                if(isset($s)){
            ?>
                <h3>File Infos</h3>
                Original File Size : <?php echo $s["Original_Infos"]["Size"]; ?> <br>
                Original File Name : <?php echo $s["Original_Infos"]["Name"]; ?> <br>
                Minified File Size : <?php echo $s["Minified_Infos"]["Size"]; ?> <br>
                
                <a href="views/minifier/tmp.txt" class="image-button primary" title="download">
                    Download
                    <i class="icon-download"></i>
                </a>                
            <?php
                }
            ?>            
        </div>
        
    </div>
