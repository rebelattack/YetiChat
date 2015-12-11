<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Database
 *
 * @author charlie
 */
class Database {
    
    var $sql;
    
    function __construct() {
        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
	$this->sql = new PDO( SQL_DNS, SQL_USER, SQL_PASS, $options );       
    }
    
    
    public function getAllShout()
    {
        $time = time() - SHOUT_TIME_LIMIT;

        $query = $this->sql->prepare("SELECT * FROM `".DB_PREFIX."chat` WHERE `timestamp` > :time ORDER by `timestamp` DESC");
        $query->bindParam(':time', $time);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    
    public function getUserName($user_id) {

        $query = $this->sql->prepare("SELECT `username` FROM `".DB_PREFIX."users` WHERE `id` = :uid LIMIT 1;");
        $query->bindParam(':uid', $user_id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }
    /* ====================================================================== */
    /* ============================= SHOUTBOX =============================== */
    /* ====================================================================== */
    public function postNewShout($msg)
    {
        $time = time();
        $time2 = time() - SHOUT_TIME_LIMIT_POST;
        $query_count = $this->sql->prepare("SELECT * FROM `".DB_PREFIX."chat` WHERE `timestamp` > :time AND `owner` = :user ORDER by `timestamp` DESC");
        $query_count->bindParam(':user', $_SESSION['id']);
        $query_count->bindParam(':time', $time2);
        $query_count->execute();
        $nb = $query_count->rowCount();

        if($nb < SHOUT_LIMIT_POST)
        {
            $query_1 = $this->sql->prepare("INSERT INTO  `".DB_PREFIX."chat` (`id` ,`owner` ,`message` ,`timestamp`) VALUES (NULL , :user,  :msg,  :time);");
            $query_1->bindParam(':user', $_SESSION['id']);
            $query_1->bindParam(':msg', $msg);
            $query_1->bindParam(':time', $time);
            $query_1->execute();
        }
    }
    
    
    
    /* ====================================================================== */
    /* ============================= SESSION ================================ */
    /* ====================================================================== */
        public function updateSession($uid, $sessid, $time) {
            $query = $this->sql->prepare("UPDATE `".DB_PREFIX."users_session` SET `end` = end + :time WHERE `uid` = :uid AND `sessid` = :sessid;");
            $query->bindParam(":time", $time);
            $query->bindParam(":uid", $uid);
            $query->bindParam(":sessid", $sessid);
            $query->execute();
        }

        public function createSession($uid, $sessid) {
            $query = $this->sql->prepare("INSERT INTO `".DB_PREFIX."users_session` (`id` ,`uid` ,`start` ,`end` ,`sessid`) VALUES (NULL , :uid, :time_start, :time_end, :sessid);");
            $time_start = time();
            $time_end = $time_start + SESSION_TIME;
            $query->bindParam(":time_start", $time_start);
            $query->bindParam(":time_end", $time_end);
            $query->bindParam(":uid", $uid);
            $query->bindParam(":sessid", $sessid);
            $query->execute();
            return $time_end;
        }

        public function closeSession($uid, $sessid) {
            $query = $this->sql->prepare("UPDATE `".DB_PREFIX."users_session` SET `sessid` = '' WHERE `uid` = :uid AND `sessid` = :sessid;");
            $query->bindParam(":uid", $uid);
            $query->bindParam(":sessid", $sessid);
            $query->execute();
        }
        
    /* ====================================================================== */
    /* ============================= CHECKERS =============================== */
    /* ====================================================================== */    
        /**
         * Check if $ref exist in users table
         * @param type $ref : username or email
         * @param type $mode ($mode = 0 => username, $mode = 1 => email)
         */
        public function checkExist($ref, $mode) {
            if($mode == 0) {
                $q = "SELECT `username` FROM `".DB_PREFIX."users` WHERE `username` = :ref LIMIT 1;";
            }
            else {
                $q = "SELECT `username` FROM `".DB_PREFIX."users` WHERE `email` = :ref LIMIT 1;";
            }

            $query = $this->sql->prepare($q);
            $query->bindParam(":ref",$ref);
            $query->execute();
            $result = $query->rowCount();

            if($result == 1) {
                return true;
            }
            return false;
        }


        public function checkExist_activate($ref, $mode) {
            if($mode == 0) {
                $q = "SELECT `username` FROM `".DB_PREFIX."users_activate` WHERE `username` = :ref LIMIT 1;";
            }
            else {
                $q = "SELECT `username` FROM `".DB_PREFIX."users_activate` WHERE `email` = :ref LIMIT 1;";
            }

            $query = $this->sql->prepare($q);
            $query->bindParam(":ref",$ref);
            $query->execute();
            $result = $query->rowCount();

            if($result == 1) {
                return true;
            }
            return false;
        }
}
