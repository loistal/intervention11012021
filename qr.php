<?php

if ($_SESSION['ds_manage_qr'] != 1) { require('logout.php'); exit; }
 
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' QR</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('qr/qrtable.php');

$qrmenu = "";
if (isset($_POST['qrmenu'])) { $qrmenu = $_POST['qrmenu']; }
elseif (isset($_GET['qrmenu'])) { $qrmenu = $_GET['qrmenu']; }
$qrmenu = d_safebasename($qrmenu);

if ($qrmenu != "") { require ('qr/' . $qrmenu . '.php'); }
else { require ('qr/qrdefault.php'); }

require ('inc/bottom.php');

?>
