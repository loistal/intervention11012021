<?php
# Security check
if (($_SESSION['ds_userid']+0) < 1) { require('logout.php'); exit; }

require ('inc/standard.php');
$ourfile = 'custom/' . d_safebasename(strtolower($_SESSION['ds_customname'])) . 'printwindow.php';
require($ourfile);
?>