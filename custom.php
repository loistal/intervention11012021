<?php
# Security check
if (($_SESSION['ds_userid']+0) < 1) { require('logout.php'); exit; }

require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
$customfilename = 'custom/' . d_safebasename(strtolower($_SESSION['ds_customname'])) . '.php';
require ($customfilename);
?>