<?php


/**
* @return float
*/
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
* @return float
*/
function price_float( $price )
{
    $price = preg_replace( '/[^0-9.,]/Uis', '', $price );
    $price = str_replace( ',', '.', $price );
    $price = (float)$price;

    return $price;    
}

/**
* @return string
*/
function redirectPage( $params ) {
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?"https":"http")."://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}".'?'.http_build_query($params);
    return '<a href = \''.$actual_link .'\'>LINK</a><script>setTimeout( function() { window.location.href = \''.$actual_link .'\'; }, 500 );</script>';
}

/**
* @return bool
*/
function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    
    return FALSE;
}

/**
* @return string
*/
function getLink( $link = '', $action = '' )
{
    if( strpos( $link , '?' ) >= 0 ) {
        $link.= '&';
    }
    else {
        $link.= '?';
    }

    $link.= 'action=' . $action
          . '&resturl=' . urlencode( $this->apiUrl ) 
          . '&restuser=' . urlencode( $this->username ) 
          . '&restkey=' . urlencode( $this->apiKey ) ;
    
    return $link;
}
