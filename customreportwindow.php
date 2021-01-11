<?php
# Security check
if (($_SESSION['ds_userid']+0) < 1 && $_SESSION['ds_clientaccess'] < 1)
{
  require('logout.php'); exit;
}

session_write_close();

require ('inc/standard.php');
$ourfile = 'custom/' . d_safebasename(strtolower($_SESSION['ds_customname'])) . 'reportwindow.php';
require($ourfile);
?>