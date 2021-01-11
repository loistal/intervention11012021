<?php

# Security check
if ($_SESSION['ds_purchaseaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Achat</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('purchase/purchasetable.php');

# Which step are we on? If not on a step, go to a menu instead.
$purchasemenu = "";
$currentstep = 0;
if (isset($_GET['purchasemenu'])) { $purchasemenu = $_GET['purchasemenu']; }
if (isset($_POST['purchasemenu'])) { $purchasemenu = $_POST['purchasemenu']; }
$purchasemenu = d_safebasename($purchasemenu);
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

if ($purchasemenu != "") { require ('purchase/' . $purchasemenu . '.php'); }
else { require ('purchase/purchasedefault.php'); }

require ('inc/bottom.php');

?>