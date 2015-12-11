<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of shoutbox
 *
 * @author charlie
 */
class Shoutbox {
        
        var $nb_shout;
        var $all_shout;
    
        function Shoutbox() {
            global $database;
            $this->all_shout = $database->getAllShout();
            $this->nb_shout = count($this->all_shout);
            
        }
        
        public function getLastShout($nb)
        {
            global $database;
            
            $res = '';
            if($this->nb_shout != 0)
            {
                $last_shout = array_slice($this->all_shout,0,$nb);
                foreach($last_shout as $shout)
                {
                    $name = $database->getUserName($shout['owner']);
                    $res .= '<div><span class="user">'.$name.':~$</span> '.$shout['message'] .'</div>';
                }
            }
            
            return $res;
        }
        
        public function getAllShout()
        {
            global $database,$SMILEYS;
                        
            $res = '';
            if($this->nb_shout != 0)
            {
                foreach($this->all_shout as $shout)
                {
                    $name = $database->getUserName($shout['owner']);
                    $res .= '<div><span class="user">'.$name.':~$</span> '.$shout['message'] .'</div>';
                }
            }
            
            return $res;
        }
        
        public function postShout($msg)
        {
            global $database;
            $message = htmlspecialchars($msg, ENT_QUOTES);
            
            if($this->all_shout[0]['message'] != $message)
            {
                $database->postNewShout($message);
            }
                        
        }
        
        public function getNbShout()
        {
            return $this->nb_shout;
        }
}