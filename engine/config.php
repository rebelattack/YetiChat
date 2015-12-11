<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL);

// SQL Credentials 
    define('SQL_DNS', 'mysql:host=127.0.0.1;dbname=BBB');
    define('SQL_USER', 'youruser');                                                 // MYSQL username
    define('SQL_PASS', 'yourpassword');                                                 // MYSQL password
    define('DB_PREFIX','');                                                  // Tables Prefix


//define("USRNM_MIN_LENGTH",5);                                                   // Username minimum length
//define("USRNM_SPECIAL", False);                                                 // Allow special chars in username
//define("PW_MIN_LENGTH",5);                                                      // Password minimum length


define("SESSION_TIME", 10*60);

define("SHOUT_TIME_LIMIT",3*3600);
define("SHOUT_TIME_LIMIT_POST",30); 
define("SHOUT_LIMIT_POST",10);