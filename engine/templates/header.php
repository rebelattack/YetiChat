<?php

if($session->isLogged == true)
{
    include("header.online.php");
    
}else{
    include("header.offline.php");
}

?>

