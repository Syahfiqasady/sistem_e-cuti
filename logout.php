<?php

include_once('common.php');
session_unset();

session_destroy();

go($site_url); 
exit;
?>
