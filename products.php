<?php

# Security check
if ($_SESSION['ds_usebyaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Produits</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('products/productstable.php');

# Which step are we on? If not on a step, go to a menu instead.
$productsmenu = "";
$currentstep = 0;
if (isset($_GET['productsmenu'])) { $productsmenu = $_GET['productsmenu']; }
if (isset($_POST['productsmenu'])) { $productsmenu = $_POST['productsmenu']; }
$productsmenu = d_safebasename($productsmenu);
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

if ($productsmenu != "") { require ('products/' . $productsmenu . '.php'); }
else { require ('products/productsdefault.php'); }

require ('inc/bottom.php');

?>