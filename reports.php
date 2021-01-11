<?php

# Security check
if ($_SESSION['ds_reportsaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Rapports</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('reports/reportstable.php');

# Which step are we on? If not on a step, go to a menu instead.
$reportsmenu = "";
$currentstep = 0;
if (isset($_GET['reportsmenu'])) { $reportsmenu = $_GET['reportsmenu']; }
if (isset($_POST['reportsmenu'])) { $reportsmenu = $_POST['reportsmenu']; }
$reportsmenu = d_safebasename($reportsmenu);
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

if ($reportsmenu != "") { require ('reports/' . $reportsmenu . '.php'); }
else { require ('reports/reportsdefault.php'); }

require ('inc/bottom.php');

?>