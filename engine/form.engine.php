<?php

class Form {
    
    private $error_array = array();                                             // Array of error reported
    public  $value_array = array();                                             // Arry of values selected in the input
    private $error_count;                                                       // nb of error in form
    
    function __construct() {        
        if(isset($_SESSION['error_array']) && isset($_SESSION['value_array'])) {
            $this->error_array = $_SESSION['error_array'];
            $this->value_array = $_SESSION['value_array'];
            $this->error_count = count($this->error_array);

            unset($_SESSION['error_array']);
            unset($_SESSION['value_array']);
        }
        else {
            $this->errorcount = 0;
        }
    }    
    
    /**
     * Add error label to the field
     * 
     * @param type $field : input name
     * @param type $error : error label
     */
    public function addError($field,$error) {
        $this->error_array[$field] = $error;
        $this->error_count = count($this->error_array);
    }
    
    /**
     * 
     * Return the error label of the field
     * 
     * @param type $field :input name
     * @return type : error label
     */
    public function getError($field) {
        
        if(array_key_exists($field,$this->error_array)) {
            return $this->error_array[$field];
        }
        else {
            return "";
        }
    }
    
    /**
     * 
     * Return the value of the input
     * 
     * @param type $field
     * @return string
     */
    public function getValue($field) {
        if(array_key_exists($field,$this->value_array)) {
            return $this->value_array[$field];
        }
        else {
            return "";
        }
    }
    
    /**
     * 
     * Return the number of error
     * 
     * @return type
     */
    public function returnErrors() {
        return $this->error_count;
    }
    
    /**
     * 
     * Return the array of error label
     * 
     * @return type
     */
    public function getErrors() {
        return $this->error_array;
    }
}