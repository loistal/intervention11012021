<?php

if ($_SESSION['ds_optionsaccess'] != 1) { require('logout.php'); exit; }

require ('inc/standard.php');

$dauphin_currentmenu = basename(__FILE__, '.php');
if (isset($_POST['optionsmenu'])) { $optionsmenu = $_POST['optionsmenu']; }
elseif (isset($_GET['optionsmenu'])) { $optionsmenu = $_GET['optionsmenu']; }
else { $optionsmenu = ''; }
$optionsmenu = d_safebasename($optionsmenu);

require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Options</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('options/optionstable.php');

$currentstep = 0; # TODO remove
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; } # TODO remove

if ($optionsmenu != "") { require ('options/' . $optionsmenu . '.php'); }
else { require ('options/optionsdefault.php'); }

require ('inc/bottom.php');

?>