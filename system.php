<?php

# Security check
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Système</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('system/systemtable.php');

# Which step are we on? If not on a step, go to a menu instead.
$systemmenu = "";
$currentstep = 0;
if (isset($_GET['systemmenu'])) { $systemmenu = $_GET['systemmenu']; }
if (isset($_POST['systemmenu'])) { $systemmenu = $_POST['systemmenu']; }
$systemmenu = d_safebasename($systemmenu);
if (isset($_GET['step'])) { $currentstep = $_GET['step'];}
elseif (isset($_POST['step'])) { $currentstep = $_POST['step']+0;}

if ($systemmenu != "") { require ('system/' . $systemmenu . '.php'); }
else { require ('system/systemdefault.php'); }

require ('inc/bottom.php');

?>
