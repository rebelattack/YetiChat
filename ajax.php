<?php


session_start();
require_once('engine/config.php');
include_once('engine/database.engine.php');
    $database = new Database();
include_once('engine/shoutbox.engine.php');
    $shoutbox = new Shoutbox();
    
    

if(@$_POST['a'] == 'getstatus')
{
    include_once('engine/users.engine.php');
        $users = new Users();
        $users->getAllStatut();
}

if(@$_POST['a'] == 'updatestatus' && is_numeric(@$_POST['s']))
{
    include_once('engine/users.engine.php');
        $users = new Users();
        $users->updateStatut(@$_POST['s']);
}
    
if(@$_POST['a'] == 'getnewshout' && is_numeric(@$_POST['i']))
{
    
    $new_count = count($database->getAllShout());    
    $lastest = $new_count - @$_POST['i'];
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
    $message = trim(htmlspecialchars(@$_POST['m']));
    echo $shoutbox->postShout($message);
}


?>
