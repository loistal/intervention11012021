<?php

if ($_SESSION['ds_salesaccess'] != 1) { require('logout.php'); exit; }

require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Vente</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('sales/salestable.php');

# TODO remove $currentstep
$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# TODO replicate $salesmenu structure to the other base modules
$salesmenu = "";
if (isset($_POST['salesmenu'])) { $salesmenu = $_POST['salesmenu']; }
elseif (isset($_GET['salesmenu'])) { $salesmenu = $_GET['salesmenu']; }
$salesmenu = d_safebasename($salesmenu);
if ($salesmenu != "") { require ('sales/' . $salesmenu . '.php'); }
else { require ('sales/salesdefault.php'); }

require ('inc/bottom.php');

?>