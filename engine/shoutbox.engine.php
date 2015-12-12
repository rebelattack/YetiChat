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
        
        /**
         * Recupère les x derniers shouts
         * @global type $database
         * @param type $nb
         * @return string
         */
        public function getLastShout($nb)
        {
            global $database;
            
            $res = '';
            if($this->nb_shout != 0)
            {
                $last_shout = array_slice($this->all_shout,-$nb);
                foreach($last_shout as $shout)
                {
                    
                    $name = $database->getUserName($shout['owner']);
                    $res .= '<div><span class="user">'.$name.':~$</span> '.$shout['message'] .'</div>';
                }
            }
            
            return $res;
        }
        
        /**
         * Recupère tous les shouts
         * @global type $database
         * @global type $SMILEYS
         * @return string
         */
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
        
        /**
         * Envoie un shout
         * @global type $database
         * @param type $msg
         * @return int
         */
        public function postShout($msg)
        {
            global $database;
            $message = htmlspecialchars($msg, ENT_QUOTES);
            
            if(end(@$this->all_shout)['message'] != $message)
            {
                $database->postNewShout($message);
                return 1;
            }
            return -1;
        }
        
        /**
         * Compte le nombre de shout
         * @return type
         */
        public function getNbShout()
        {
            return $this->nb_shout;
        }
}