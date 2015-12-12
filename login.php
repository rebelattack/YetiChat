<?php
// Basic setup :
    session_start();
    require_once("engine/config.php");

// Database library : 
    require_once("engine/database.engine.php");
    require_once("engine/login/sql.php");
        $database = new Sql();
        
// Form library :
    require_once("engine/form.engine.php");
        $form = new Form();        
        
// Session library :
    require_once("engine/session.engine.php");
        $session = new Session();
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Blog</title>
        <link href="css/base.css" rel="stylesheet" type="text/css">
        <link href="css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body>
            
    	<div id="wrapper">
            <div id="header">
                <?php include_once("engine/templates/header.php"); ?>
            </div>
            
    		<div id="content">
                    <form method="post" class="login">
                        <h1>Connexion</h1>
                        <input type="hidden" name="a" value="a3">

                        <input class="input-large" type="text" name="u" placeholder="Pseudo" value="<?php echo $form->getValue("u"); ?>" /> 
                                  <?php                                  
                                  if(@$form->getError("u") != null)
                                  {
                                    echo '<br><span class="input-error">'.$form->getError("u")."</span>"; 
                                  }
                                  ?><br>
                        <input class="input-large" type="password" placeholder="Mot de passe" name="p" value="<?php echo $form->getValue("p"); ?>" />
                                  <?php                                  
                                  if(@$form->getError("p") != null)
                                  {
                                    echo '<br><span class="input-error">'.$form->getError("p")."</span>"; 
                                  }
                                  ?><br>

                        <input type="submit" class="submit-btn" value="Submit">
                    </form> 
                </div>
                
    		<div id="footer"> 
                    <?php include_once("engine/templates/footer.php"); ?>
                </div>
    	</div>
    
    </body>
</html>
