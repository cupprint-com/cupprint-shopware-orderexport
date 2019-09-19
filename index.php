<?php
/**
 * @version: 0.0.1
 */
$tplAreas = array(
    'ui' => array(),
    'customerDetails' => array(),
    'iframe' => array(),
    'content' => array(),
    'debug' => array()
);

/**
 * config & includes
 */
include( "config.php");
include( "functions.php");
include( "ApiClient.php");

/**
 * Validate Input
 */
$action = '';
if( array_key_exists( 'action', $_REQUEST ) ) {
    $action = $_REQUEST['action'];
}

if( array_key_exists( 'resturl', $_REQUEST ) ) {
    if ( strlen($_REQUEST['resturl']) > 1024 ) {
        die( 'resturl to long (max. 1024)' );
    }
    $restUrl = $_REQUEST['resturl'];
}
if( array_key_exists( 'restuser', $_REQUEST ) ) {
    $restUser = $_REQUEST['restuser'];
}
if( array_key_exists( 'restkey', $_REQUEST ) ) {
    if ( strlen($_REQUEST['restkey']) != 40 ) {
        die( 'restkey to long (=40)' );
    }
    $restKey = $_REQUEST['restkey'];
}



/**
 * Convert settings
 */
$debug = true;
$redirect = '';



/**
 * Check API-Access
 */
if( $restUrl != '' && $restUser != '' && $restKey != '' )  {
    // create Client
    $client = new ApiClient( $restUrl, $restUser, $restKey );
    $oUser = '';
    $params = [ 'filter' => [ [
                    'property' => 'username',
                    'expression' => '=',
                    'value' => $restUser
                ] ] ];
    $oUser = json_decode( $client->get( 'users', $params ), true );

    if( array_key_exists( 'total', $oUser ) && $oUser['total'] > 0 ) {
        $userdetails = '<div class="alert alert-success w-100 pt-2 pb-2 text-center" role="alert">'
                            . '<b>' . $oUser['data'][0]['name'] . '</b> (' . $oUser['data'][0]['id'] . ') <a href="mailto:' . $oUser['data'][0]['email'] . '">Mail</a>'
                        . '</div>';
        $tplAreas['usersidebar'][] = $userdetails;
    }
    else {
        $client = false;
    }
}
else {
    $client = false;
}



if( $client != false )  {



    $ui = '';



        /**
         * Get Data from DB
         */
        $sql = "SELECT
                *
            FROM
                cp_order_export
            ORDER by
                lastOrderId ASC";

        $result = $sqlhandle->query($sql);
        
        if( $result->num_rows > 0 ) {
            // output data of each row
            $ui.= '<table width="100%">'
                    . "<tr>"
                        . "<td>id</td>"
                        . "<td>timestamp</td>"
                        . "<td>lastOrderId</td>"
                        . "<td>Log</td>"
                    . "</tr>";
            while( $row = $result->fetch_assoc() ) {

                $link_params = array(
                    'action' => 'openfile' ,
                    'file' => $row["id"] ,
                    'resturl' => $_REQUEST['resturl'] ,
                    'restuser' => $_REQUEST['restuser'] ,
                    'restkey' => $_REQUEST['restkey']
                );

                $log_params = array(
                    'action' => 'openlog' ,
                    'file' => $row["id"] ,
                    'resturl' => $_REQUEST['resturl'] ,
                    'restuser' => $_REQUEST['restuser'] ,
                    'restkey' => $_REQUEST['restkey']
                );

                $ui.= "<tr>"
                        ."<td nowrap>". $row["id"] ."</td>"
                        .'<td nowrap><a target="_blank" href="'.
                        
                        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?"https":"http")
                        ."://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}".'?'.http_build_query($link_params)

                        .'">'. $row["timestamp"] ."</a></td>"
                        ."<td nowrap>". $row["lastOrderId"] ."</td>"
                        .'<td nowrap><a target="_blank" href="'.
                        
                        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?"https":"http")
                        ."://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}".'?'.http_build_query($log_params)

                        .'">Link'."</a></td>"
                    . "</tr>";
                $lastid = $row["id"];
                $lastorderid = $row["lastOrderId"];
            }
            $ui.= '</table>';

            $link_params = array(
                'action' => 'export' ,
                'resturl' => $_REQUEST['resturl'] ,
                'restuser' => $_REQUEST['restuser'] ,
                'restkey' => $_REQUEST['restkey']
            );
            $ui.= '<a href="'.
                        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on'?"https":"http")
                        ."://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}".'?'.http_build_query($link_params)
                    .'">Start Export</a>';
        }
    $tplAreas['ui'] = array();
    $tplAreas['ui'][] = $ui;
}
else {
    /**
     * Login Form
     */
    $tplAreas['usersidebar'][] = '<form action="#" method="post">'
                                    . '<input name="action" value="" type="hidden">'

                                        . '<div class="form-group">'
                                            . '<label for="resturl">URL</label>'
                                            . '<input type="text" class="form-control" name="resturl" id="resturl" value="' . $restUrl . '" placeholder="https://.../api/">'
                                        . '</div>'
                                        . '<div class="form-group">'
                                            . '<label for="restuser">User</label>'
                                            . '<input type="text" class="form-control" name="restuser" id="restuser" value="' . $restUser . '" placeholder="name123">'
                                        . '</div>'
                                        . '<div class="form-group">'
                                            . '<label for="restkey">API-Key</label>'
                                            . '<input type="text" class="form-control" name="restkey" id="restkey" value="' . $restKey . '" placeholder="">'
                                        . '</div>'

                                    . '<input name="Submit" value="Submit" type="submit">'
                                . '</form>';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .listReturn {
            height: 250px;
            width: 100%;
            overflow-y: auto;
            white-space: pre;
        }
    </style>
    <title>User Price Importer</title>
</head>
<body>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="usersidebar col-12 col-md-4 pt-4 order-md-1">
                <?php
                    echo implode( $tplAreas['usersidebar'] );
                ?>
            </div>

            <div class="content col-12 col-md-8 pt-4">
                <div class="row">
                    <?php
                        echo implode( $tplAreas['ui'] );
                    ?>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="content col-12">
                <div class="row">
                    <?php
                        echo implode( $tplAreas['customerDetails'] );
                        echo implode( $tplAreas['iframe'] );
                        echo implode( $tplAreas['content'] );
                    ?>
                </div>
            </div>

        </div>
        <div class="row">
            <?php
                echo implode( $tplAreas['debug'] );
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php echo $redirect; ?>
</body>
</html>
<?php

$sqlhandle->close();
