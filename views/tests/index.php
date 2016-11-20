<!DOCTYPE html>
<html>
    <head>
        <title>Welcome Back </title>
        <link rel="stylesheet" href="public/css/metro-bootstrap.css">
        <link href="public/css/metro-bootstrap-responsive.css" rel="stylesheet">
        <link href="public/css/iconFont.css" rel="stylesheet">
        <link href="public/css/docs.css" rel="stylesheet">  
        
        <script src="public/js/jquery/jquery.min.js"></script>
        <script src="public/js/jquery/jquery.widget.min.js"></script>
        <script src="public/js/metro/metro.min.js"></script>
        <script src="public/js/functions.js"></script>
    </head>
    <body class="metro">
        <nav class="navigation-bar dark">
            <nav class="navigation-bar-content">
                <item class="element">
                    <a href="http://<?php echo URL; ?>"><strong><i class=" icon-home on-left"></i>H<sub>2015</sub></strong></a>
                </item>
                <item class="element-divider"></item>                
                <item class="element right page-header">Welcome To My WEB UTILITIES V1.0</item>
                <item class="element-divider"></item>
                <item class="element right page-header">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</item>
                <item class="element-divider"></item>                
                <item class="element right">
                    <?php 
                        $d = Session::get("selected_db");
                        $to = (isset($d))?Session::get("selected_db"):"No data base ";
                        echo "<em>Currently connected to <u><strong>".$to."</strong></u></em>";
                    ?>                
                </item>
                <item class="element-divider"></item>
                <item class="element right"><a href="http://<?php echo URL; ?>?logout"><i class="icon-locked-2 on-right"></i></a></item>
            </nav>            
        </nav>
