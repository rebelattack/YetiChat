<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cmd
 *
 * @author charlie
 */
class Cmd {
    
    
    public function __construct($_cmd, $_args){
        switch (@$_POST["a"]) {                                                 // Checking if any action is triggered
            case "top":
                $this->top($_args);
                break;
            default:
                echo "unknow command";
                break;
        } 
    }
    
    private function top(){
        echo "top cmd";
    }
}