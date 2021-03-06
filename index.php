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
        <script src="js/chat.js"></script>
        <script src="js/statut.js"></script>
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
