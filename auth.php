<?php
/*
 * FreeLadder Ladder Server
 * http://freeladder.org
 *
 * Copyright 2010, Jim Kalafut
 * Released under the MIT license.
 *
 */
session_start();
 
function verifyAuthorization($redirect=true) 
{
 	global $current_user;
    
    if( !isset($_SESSION['id']) ) {
        if($redirect) {
            header("Location: login.php");
            exit();
        }    
    } else {
        $current_user = $_SESSION['id'];     
    }
}

function authorize($id) 
{
    $_SESSION['id'] = $id;
    $_SESSION['version'] = $CURRENT_VERSION;
    session_regenerate_id();
}

function deauthorize()
{
    session_destroy();
}
  
?>
 