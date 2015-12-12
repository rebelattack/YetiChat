<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sql
 *
 * @author charlie
 */
class Sql extends Database {
    
    
    public function getInfosUserLogin($username,$password) {
        $pass = hash('sha256',$password);
        $query = $this->sql->prepare("SELECT * FROM `".DB_PREFIX."users` WHERE `password` = :pass AND `username` = :pseudo LIMIT 1;");
        $query->bindParam(':pass', $pass);
        $query->bindParam(':pseudo', $username);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function checkLogin($pseudo,$password) {
        $pass = hash('sha256',$password);
        $query = $this->sql->prepare("SELECT * FROM `".DB_PREFIX."users` WHERE `password` = :pass AND `username` = :pseudo LIMIT 1;");
        $query->bindParam(':pass', $pass);
        $query->bindParam(':pseudo', $pseudo);
        $query->execute();
        $nb = $query->rowCount();
        if($nb == 1)
        {
            return true;
        }
        return false;
    }
    
    public function checkActivation($pseudo) {
        $query = $this->sql->prepare("SELECT `ref` FROM `".DB_PREFIX."users_activate` WHERE `username` = :pseudo AND `ref` != '' LIMIT 1;");
        $query->bindParam(':pseudo', $pseudo);
        $query->execute();
        $nb = $query->rowCount();
        if($nb == 1)
        {
            return true;
        }
        return false;
    }
    
}
