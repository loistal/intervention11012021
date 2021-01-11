<?php
 
# Security check
if ($_SESSION['ds_hraccess'] != 1) { require('logout.php'); exit; }
 
# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' RH</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('hr/hrtable.php');

# Which step are we on? If not on a step, go to a menu instead.
$hrmenu = "";
$currentstep = 0;
if (isset($_POST['hrmenu'])) { $hrmenu = $_POST['hrmenu']; }
elseif (isset($_GET['hrmenu'])) { $hrmenu = $_GET['hrmenu']; }
$hrmenu = d_safebasename($hrmenu);

if (isset($_POST['step'])) { $currentstep = $_POST['step']+0;} 
else if (isset($_GET['step'])) { $currentstep = $_GET['step']+0;}
if (isset($_POST['stepitem'])) { $currentstepitem = $_POST['stepitem']+0; }
else if (isset($_GET['stepitem'])) { $currentstepitem = $_GET['stepitem']+0; }

if ($hrmenu != "") { require ('hr/' . $hrmenu . '.php'); }
else { require ('hr/hrdefault.php'); }

require ('inc/bottom.php');

?>
