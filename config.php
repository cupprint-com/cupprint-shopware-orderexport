<?php

/**
 * Error-Reporting & Co.
 */
error_reporting( E_ALL );
ini_set ('display_errors', 'On');



/** 
 * Rest
 */
// URL of shopware REST server
$restUrl    = '';    // http://www.oliverlohkemper.de/shopware/api/
// Username
$restUser   = '';    // restuser
// User's API-Key
$restKey    = '';    // armydPzSE9lC5VfIEGeoLo9lL9mO1zrWBES6fd6g



/**
 * Login
 */
$userName = '';
$userPass = '';

$login = false;



/** 
 * Config
 */
$sqlConfig = include('../config.php');

$sqlhandle = new mysqli(
    $sqlConfig['db']['host'].':'.$sqlConfig['db']['port'] ,
    $sqlConfig['db']['username'] ,
    $sqlConfig['db']['password'] ,
    $sqlConfig['db']['dbname']
);


/** 
 * For Order-Export 
 */