<?php
// Basic setup :
    session_start();
    require_once("engine/config.php");

// Database library : 
    require_once("engine/database.engine.php");
    require_once("engine/logout/sql.php");
        $database = new Sql();
        
// Form library :
    require_once("engine/form.engine.php");
        $form = new Form();        
        
// Session library :
    require_once("engine/session.engine.php");
        $session = new Session();
        $session->logout();
        
    header("Location: login.php");
?>

