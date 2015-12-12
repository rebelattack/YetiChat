<?php
// Basic setup :
    session_start();
    require_once("engine/config.php");

// Database library : 
    require_once("engine/database.engine.php");
    require_once("engine/index/sql.php");
        $database = new Sql();
        
// Form library :
    require_once("engine/form.engine.php");
        $form = new Form();        
        
// Session library :
    require_once("engine/session.engine.php");
        $session = new Session();
?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Chat IRC</title>
        <link href="css/base.css" rel="stylesheet" type="text/css">
        <link href="css/index.css" rel="stylesheet" type="text/css">
        <script src="js/jquery.js"></script>
        <script src="js/smoothie.js"></script>
        <script src="js/chat.js"></script>
        <script src="js/statut.js"></script>
        <script src="js/chart.js"></script>
    </head>
    <body>
        
    	<div id="wrapper">
            <div id="header">
                <?php include_once("engine/templates/header.php"); ?>
            </div>
            
    		<div id="content">
                    <?php
                        include_once('engine/shoutbox.engine.php');
                            $shoutbox = new Shoutbox();

                            $nbshout = $shoutbox->getNbShout();
                    ?>
                    <div class="chat" data-nb="<?php echo $nbshout;?>">
                        <?php
                            echo $shoutbox->getAllShout();
                        ?>
                    </div>
                    <div class="users">
                        <?php
                        include_once('engine/users.engine.php');
                            $users = new Users();
                            
                            $users->getAllStatut();
                    ?>
                    </div>
                    <div class="monitor">
                        <span>CPU usage :</span>
                        <canvas id="cpuusage" width="225" height="41"></canvas>
                        <span>RAM & SWAP usage :</span>
                        <canvas id="ramusage" width="225" height="41"></canvas>
                        <span>Upload & Download usage :</span>
                        <canvas id="bandwidthusage" width="225" height="41"></canvas>
                        <span style="border-left:3px solid #d35400;">CPU %</span><br>
                        <span style="border-left:3px solid #2980b9;">RAM %</span>
                        <span style="border-left:3px solid #27ae60;">SWAP %</span><br>
                        <span style="border-left:3px solid #16a085;">Download Ko/s</span>
                        <span style="border-left:3px solid #8e44ad;">Upload Ko/s</span>
                    </div>
                    <div class="input-chat">
                        <textarea class="input-large"/></textarea><input type="submit" class="submit-btn" value="Submit">
                    </div>
                </div>
                
    		<div id="footer"> 
                    <?php include_once("engine/templates/footer.php"); ?>
                </div>
    	</div>
    
    </body>
</html>
