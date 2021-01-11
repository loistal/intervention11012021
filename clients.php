<?php

# TODO redo all main module files like this one

if ($_SESSION['ds_clientsaccess'] != 1) { require('logout.php'); exit; }

require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Clients</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('clients/clientstable.php');

$currentstep = 0; # TODO remove this in all modules
if (isset($_POST['step'])) { $currentstep = (int) $_POST['step']; } # TODO remove this in all modules

if (isset($_POST['clientsmenu'])) { $clientsmenu = d_safebasename($_POST['clientsmenu']); }
elseif (isset($_GET['clientsmenu'])) { $clientsmenu = d_safebasename($_GET['clientsmenu']); }
else { $clientsmenu = ''; }

if ($clientsmenu != '') { require ('clients/' . $clientsmenu . '.php'); }
else { require ('clients/clientsdefault.php'); }

require ('inc/bottom.php');

?>
