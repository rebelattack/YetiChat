<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users
 *
 * @author charlie
 */
class Users {
    
    //  
    // 3 green : active
    // 2 orange : active in last 5mn
    // 1 red : busy
    // 0 black : offline
    public function getAllStatut(){
        global $database,$session;
        
        $users = $database->getUsersStatut();
        foreach($users as $user) {
            if(!$this->checkUserSession($user['id'])){
                $statut = 0;
            }
            else {
                $statut = $user['statut'];
            }
            echo '<span class="statut '.$this->getColor($statut).'">'.$user['username'].'</span><br>';
        }       
    }
    
    private function checkUserSession($uid){
        global $database;
        $session = $database->getSession($uid);
        $time = time();

        if($session['end'] < $time) {
            return false;
            $database->updateStatut($uid, 0);
        }
        
        return true;
    }
    
    public function updateStatut($statut){
        global $database;
        $database->updateStatut($_SESSION['id'], $statut);
    }
    
    private function getColor($statut){
        if($statut == 3){
            return "green";
        }
        else if($statut == 2){
            return "orange";
        }
        else if($statut == 1){
            return "red";
        }
        else {
            return "black";
        }
    }
    
}
