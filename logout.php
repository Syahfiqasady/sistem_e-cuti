<?php

include_once('common.php');
// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page or homepage
go($site_url); 
exit;
?>
