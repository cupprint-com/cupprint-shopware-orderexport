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
$exportDir = './export/';
$logDir = './log/';

// CSV Seperator
$csv_sep = ';';

$csvTitle = array(
   'empty-1'=>'-',
   'Bestell-ID' => 'Bestell-ID', 
   'Rechnungsdatum' => 'Rechnungsdatum', 
   'b_Firma' => 'Firma', 
   'b_Name' => 'Name', 
   'b_Vorname' => 'Vorname', 
   'b_Strasse' => 'Strasse', 
   'b_PLZ' => 'PLZ', 
   'b_Ort' => 'Ort', 
   'b_Land' => 'Land', 
   'b_Telefon' => 'Telefon', 
   'Mailadresse' => 'Mailadresse', 
   'VAT ID' => 'VAT ID', 
   'p_Anzahl' => 'Anzahl', 
   'p_Produktbezeichnung' => 'Produktbezeichnung', 
   'Projectname' => 'Projectname', 
   'p_Material' => 'Material', 
   'p_Einzelpreis' => 'Einzelpreis', 
   'p_Summe' => 'Summe', 
   'Zahlungsart' => 'Zahlungsart', 
   's_Firma' => 'Firma', 
   's_Name' => 'Name', 
   's_Vorname' => 'Vorname', 
   's_Strasse' => 'Strasse', 
   's_PLZ' => 'PLZ', 
   's_Ort' => 'Ort', 
   's_Land' => 'Land', 
   's_Telefon' => 'Telefon'
);
