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

$_REQUEST['resturl'] = 'https://shopware.cupprint.com/api/';

if( file_exists( dirname(__FILE__) . '/development.txt' ) ) {
   $_REQUEST['resturl'] = 'https://develop.cupprint.com/api/';
}

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
try {
   $sqlhandle = new PDO("mysql:host=" . $sqlConfig['db']['host'] . ';port=' . $sqlConfig['db']['port'] . ";dbname=" . $sqlConfig['db']['dbname'], $sqlConfig['db']['username'], $sqlConfig['db']['password']);
}
catch(PDOException $e) {
   echo "Connection failed: " . $e->getMessage();
}


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
