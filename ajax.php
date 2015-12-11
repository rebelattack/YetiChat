<?php


session_start();
require_once('engine/config.php');
include_once('engine/database.engine.php');
    $database = new Database();
include_once('engine/shoutbox.engine.php');
    $shoutbox = new Shoutbox();
    

    
if(@$_GET['a'] == 'getnewshout' && is_numeric(@$_GET['i']))
{
    
    $new_count = count($database->getAllShout());    
    $lastest = $new_count - @$_GET['i'];
    if($lastest <= 0)
    {
        $lastest = 0;
        echo '-1';
    }
    else
    {   
        echo $shoutbox->getLastShout($lastest);
    }
	
}

if(@$_POST['a'] == 'postnewshout' && @$_POST['m'] != "")
{
	$message = trim(htmlspecialchars($_POST['m']));
    $shoutbox->postShout($message);
}


?>
