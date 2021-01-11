<?php

# Security check
if ($_SESSION['ds_adminaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Admin</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('admin/admintable.php');

# Which step are we on? If not on a step, go to a menu instead.
$adminmenu = "";
$currentstep = 0;
if (isset($_GET['adminmenu'])) { $adminmenu = $_GET['adminmenu']; }
if (isset($_POST['adminmenu'])) { $adminmenu = $_POST['adminmenu']; }
$adminmenu = d_safebasename($adminmenu);
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

if ($adminmenu != "") { require ('admin/' . $adminmenu . '.php'); }
else { require ('admin/admindefault.php'); }

require ('inc/bottom.php');

?>
