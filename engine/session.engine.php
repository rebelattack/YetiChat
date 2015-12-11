<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of session
 * 
 * @author charlie
 */
class Session {
    
    public $isLogged;
    
    public function __construct() {
        
        $this->isLogged = $this->checkSession();                                //checking session
        $this->checkUrl();                                                      // checking url
                
        
        switch (@$_POST["a"]) {                                                 // Checking if any action is triggered
            case "a1":
                $this->register();
                break;
            case "a2":
                $this->activate();
                break;
            case "a3":
                $this->login();
                break;              
        } 
        
    }    
    
    private function register() {
        global $database, $form;
        
        $username = trim(htmlspecialchars($_POST['u']));
        $password1 = trim(htmlspecialchars($_POST['p1']));
        $password2 = trim(htmlspecialchars($_POST['p2']));
        $email = trim(htmlspecialchars($_POST['e']));        
        // Checking Username :
            if(!isset($username) || $username == "") {
                $form->addError("u","Veuillez entrer un pseudo");
            }
            else {
                if(strlen($username) < USRNM_MIN_LENGTH) {
                    $form->addError("u","Pseudo trop court");
                }
                else if(!USRNM_SPECIAL && preg_match('/[^0-9A-Za-z]/',$username)) {
                    $form->addError("u","Les caractères spéciaux ne sont pas autorisés");
                }
                else if($database->checkExist($username,0)) {
                    $form->addError("u","Ce pseudo existe déjà");
                }
                else if($database->checkExist_activate($username,0)) {
                    $form->addError("u","Ce pseudo existe déjà");
                }
            }        
        //Checking password :
            if(!isset($password1) || $password1 == "") {
                $form->addError("p1","Veuillez entrer un mot de passe");
            }
            else {
                if(strlen($password1) < PW_MIN_LENGTH) {
                    $form->addError("p1","Mot de passe trop court");
                }
                else if($password1 == $username) {
                    $form->addError("p1","Mot de passe trop simple");
                }
                else if($password1 != $password2) {
                    $form->addError("p2","Les deux mots de passe sont différents");
                }
            }        
        // Checking Email :
            if(!isset($email) || $email == "") {
                $form->addError("e","Veuillez entrer une adresse email");
            }
            else {
                if(!$this->checkEmail($email)) {
                    $form->addError("e","Email invalide");
                }
                else if($database->checkExist($email,1)) {
                    $form->addError("e","Cet email exite déjà");
                }
                else if($database->checkExist_activate($email,1)) {
                    $form->addError("e","Cet email exite déjà");
                }
            }
        // If there are any errors
            if($form->returnErrors() > 0) {
                $_SESSION['error_array'] = $form->getErrors();
                $_SESSION['value_array'] = $_POST;
                header("Location: register.php");
            }
            else {
                $password = hash('sha256',$password1);
                if(EMAIL_VALIDATION) {
                    $ref = $this->generateRandStr(5);
                    $database->register_activate($username,$password,$email,$ref);

                    // TO-DO : send validation email
                }
                else {
                    $database->register_activate($username,$password,$email,"");
                }
                header("Location: activate.php");
            }        
    }
    
    private function activate() {
        global $database, $form;
        
        if(isset($_GET['act'])) {
            $act = trim(htmlspecialchars(@$_GET['act']));
        }
        else {
            $act = trim(htmlspecialchars($_POST['act']));
        }
        
        // Checking Activation code :
            if(!isset($act) || $act == "") {
                $form->addError("act","Veuillez entrer votre code d'activation");
            }
            else if(!$database->checkAct($act)) {
                $form->addError("act","Code d'activation incorrect");
            }
        
        // If there are any error :
            if($form->returnErrors() > 0) {
                $_SESSION['error_array'] = $form->getErrors();
                $_SESSION['value_array'] = $_POST;
                header("Location: activate.php");
            }
            else {
                $infos = $database->getActInfo($act);
                $database->doActivation($act);
                $database->register( $infos['username'],  $infos['password'],  $infos['email']);
                header("Location: login.php");
            }
    }
    
    private function login() {
        global $form,$database,$session;
        
        $pseudo = trim(htmlspecialchars($_POST['u']));
        $password = trim(htmlspecialchars($_POST['p']));
        
        if(!isset($pseudo) || $pseudo == "") {
            $form->addError("u","Entrer votre pseudo");
        }
        else if(!$database->checkActivation($pseudo)) { // if user is activate                  
            if(!$database->checkExist($pseudo,0)) {              
                $form->addError("u","Joueur introuvable");
            }
            else {
                if(!isset($password) || $password == "") {
                    $form->addError("p","Veuillez entrer un mot de passe");
                }
                else if(!$database->checkLogin($pseudo,$password)) {
                    $form->addError("p","Erreur combinaison pseudo/mot de passe");
                }
            }
        }
        else {   
            if($database->checkExist_activate($pseudo,0)) {
                $form->addError("act",$pseudo);
            }
        }       
        
        if($form->returnErrors() > 0) {
                $_SESSION['error_array'] = $form->getErrors();
                $_SESSION['value_array'] = $_POST;
                header("Location: login.php");
        }
        else {
            $user_infos = $database->getInfosUserLogin($pseudo,$password);
            $sessid = $this->generateRandStr(15);

            $_SESSION['id'] = $user_infos['id'];
            $_SESSION['username'] = $user_infos['username'];
            $_SESSION['sess_id'] = $sessid;
            $_SESSION['sess_end'] = $database->createSession($user_infos['id'],$sessid);

            header("Location: index.php");           
        }
    }
    
    public function logout() {               
        global $database;
        if($this->isLogged) {
            $database->closeSession($_SESSION['id'],$_SESSION['sess_id']);        
            $_SESSION = array();
            session_destroy();
            session_start();
            $this->isLogged = $this->checkSession();
        }
    }
        
    private function checkEmail($email) {
        $regexp="/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
        
        if ( !preg_match($regexp, $email) ) {
            return false;
        }
        return true;    
    }
    
    private function checkUrl() {
        $explode = explode("/", $_SERVER['SCRIPT_NAME']);
        $i = count($explode) - 1;
        $current_page = $explode[$i];
                
        $offline_page_array = array("login.php", "activate.php", "register.php");
        
        if(!$this->isLogged && $current_page != "logout.php") {
            if(!in_array($current_page, $offline_page_array)) {
                header("Location: login.php");
            }
        }
        else {
            if(in_array($current_page, $offline_page_array)) {
                header("Location: index.php");
            }
        }
        
    }
    
    private function checkSession() {
        
        global $database;
        
        if(isset($_SESSION['id']) && isset($_SESSION['sess_id'])) {
            $time = time();
            
            if($_SESSION['sess_end'] < $time) {
                $this->Logout();
                return false;
            }
            else {
                $add_time = $time + SESSION_TIME - $_SESSION['sess_end']; // Calcul of the time to add to the session                
                
                $database->updateSession($_SESSION['id'],$_SESSION['sess_id'],$add_time);
                $_SESSION['sess_end'] += $add_time;
                return true;
            }            
        }
       
        return false;
    }
    
    private function generateRandStr($length){
        $randstr = "";
        for($i=0; $i<$length; $i++) {
            $randnum = mt_rand(0,61);
            if($randnum < 10) {
                    $randstr .= chr($randnum+48);
            }
            else if($randnum < 36) {
                $randstr .= chr($randnum+55);
            }
            else {
                $randstr .= chr($randnum+61);
            }
        }
        return $randstr;
   }
}
