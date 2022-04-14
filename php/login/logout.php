<?php
session_start();

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();

// Redirect to login page
header("location: https://sevensales.azurewebsites.net/");
exit;
?>