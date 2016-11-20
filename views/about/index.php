<div style="width: 80%;float: right">
    <center class="tile-area-grayed" style="width: 98%"><h1><strong><i class="icon-help-2 fg-white"></i> About Web Utilities V1.0</strong></h1></center>

    <div class="balloon right" style="width: 90%;left:2%;top:10px">
        <div class="padding20">            
            <div class="panel" data-role="panel">
                <div class="panel-header">
                    <i class="icon-info"></i> About
                </div>
                <div class="panel-content">
                    This tool was build up from scratch by <strong>Hermanho alias El manifico &copy; 2015</strong>
                    <hr>
                    <h2>Requirements</h2>
                        <div class="listview small">
                            <a href="#" class="list">
                                <div class="list-content"><em>1 - </em> Enable apache <code>rewrite_module</code></div>
                            </a>

                            <a href="#" class="list">
                                <div class="list-content"><em>2 - </em> Edit <code><?php echo URL."config.php"; ?></code>
                                    <u><strong>NB</strong></u> : in the <strong>DD_NAME</strong>, assigne any existing DB
                                </div>
                            </a>                            
                            
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="balloon left" style="width: 90%;left:2%;top:10px">
        <div class="padding20">            
            <div class="panel" data-role="panel">
                <div class="panel-header">
                    <i class="icon-help"></i> how it works
                </div>
                <div class="panel-content">
                    <div class="tab-control" data-role="tab-control" data-effect="slide">
                        <ul class="tabs">
                            <li class="active"><a href="#_page_1"><i class="icon-database"></i> DataBase Manager</a></li>
                            <li><a href="#_class_generator"><i class="icon-cog"></i> Class Generator</a></li>
                            <li><a href="#_crud_generator"><i class="icon-code"></i> C.R.U.D Generator</a></li>
                            <li><a href="#_minifier"><i class="icon-code"></i> Script Minifier</a></li>
                            <li class="place-right"><a href="#_page_4"><i class="icon-cog"></i></a></li>                            
                        </ul>

                        <div class="frames">
                            <div class="frame" id="_page_1">
                                <hr>
                                This menu enables you select a Data Base on which to work
                                
                                <img src="public/images/db_manager.png" class="shadow"><br><br>
                                just click on the <strong>Use</strong> button of the desired Data Base :)
                            </div>
                            <div class="frame" id="_class_generator">
                                This menu enables you generate simple PHP OO classes(including getters/setters) for your desired tables of your previouslly selected BD
                                <img src="public/images/class_manager.png" class="shadow"><br><br>
                                Here, you find all the tables of your DB in the field marked in red. You can leave de default options, ie all tables are selected, or select desired
                                tables to generate, then click the <strong>Generate</strong> button. The following window appears to show you generation status : 
                                <img src="public/images/class_manager2.png" class="shadow"><br><br>
                                Generated code is stored in : <br>
                                <code>Classes : <?php echo GENERATED_CLASSES; ?></code> <br>
                                <code>Models : <?php echo GENERATED_MODELS; ?></code> <br>
                            </div>
                            
                            <div class="frame" id="_crud_generator">
                                <h2>And here comes the best !!!</h2>
                                This menu creates a fully functional PHP OO MVC CRUD(Create Read Update Delete) application from your selected DB and Tables.
                                <br><img src="public/images/crud_generator.png" class="shadow"><br><br>
                                Here, you find all the tables of your DB. You can leave the default options, ie all tables are selected, or select desired
                                tables to generate, then click the <strong>Generate</strong> button. The following window appears to show you generation status :                                 
                                <br><img src="public/images/crud_generator2.png" class="shadow"><br><br>
                                Generated Project is stored in : <br>
                                <code><?php echo GENERATED_CRUD; ?></code> <br>
                            </div>
                            
                            <div class="frame" id="_minifier">
                                This menu trims up empty spaces form selected file and returns a new file which has a reduced size 
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <script>
        $(doccument).ready(function () {
            $(".tab-control").tabcontrol();
        });
        $(function () {
            $("#tab-with-event").tabcontrol().bind("tabcontrolchange", function (event, frame) {
                var id = $(frame).attr("id");
                $(frame).html("Show in time " + (new Date()).getTime() + " frame id:" + id);
            })
        })
    </script>