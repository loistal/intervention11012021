<?php

if ($_SESSION['ds_warehouseaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Entrepôt</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('warehouse/warehousetable.php');

$warehousemenu = "";
if (isset($_GET['warehousemenu'])) { $warehousemenu = $_GET['warehousemenu']; }
if (isset($_POST['warehousemenu'])) { $warehousemenu = $_POST['warehousemenu']; }
$warehousemenu = d_safebasename($warehousemenu);

if ($warehousemenu != "") { require ('warehouse/' . $warehousemenu . '.php'); }
else { require ('warehouse/warehousedefault.php'); }

require ('inc/bottom.php');

?>