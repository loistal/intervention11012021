<?php

# Security check
if ($_SESSION['ds_deliveryaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Livraison</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('delivery/deliverytable.php');

# Which step are we on? If not on a step, go to a menu instead.
$deliverymenu = "";
if (isset($_GET['deliverymenu'])) { $deliverymenu = $_GET['deliverymenu']; }
if (isset($_POST['deliverymenu'])) { $deliverymenu = $_POST['deliverymenu']; }
$deliverymenu = d_safebasename($deliverymenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }
if ($deliverymenu != "") { require ('delivery/' . $deliverymenu . '.php'); }
else { require ('delivery/deliverydefault.php'); }

require ('inc/bottom.php');

?>