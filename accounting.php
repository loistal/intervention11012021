<?php

# Security check
if ($_SESSION['ds_accountingaccess'] != 1) { require('logout.php'); exit; }

# Build web page
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');
require ('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Compta</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
require ('inc/menu.php');
require ('accounting/accountingtable.php');

$accountingmenu = '';
if (isset($_POST['accountingmenu'])) { $accountingmenu = $_POST['accountingmenu']; }
elseif (isset($_GET['accountingmenu'])) { $accountingmenu = $_GET['accountingmenu']; }
$accountingmenu = d_safebasename($accountingmenu);
if ($accountingmenu != "") { require ('accounting/' . $accountingmenu . '.php'); }
else { require ('accounting/accountingdefault.php'); }

require ('inc/bottom.php');

?>