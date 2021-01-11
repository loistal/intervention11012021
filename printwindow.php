<?php

# need refactor

# Security check
$ok = 0; $logmeout = 0;
if ($_SESSION['ds_userid'] > 0) { $ok = 1; $token = ''; }
elseif (isset($_GET['token']) && isset($_GET['invoiceid']))
{
  $_SESSION['ds_dauphininstance'] = $_GET['instancename'];
  $ok = 1;
  $logmeout = 1;
}
elseif ($_SESSION['ds_clientaccess'] && $_SESSION['ds_customname'] == 'Wing Chong') # TODO restrict clientaccess viewing of invoices somehow
{
  $ok = 1; $token = '';
}
if ($ok == 0) { require('logout.php'); exit; }

require ('inc/standard.php');

if (isset($_GET['token']))
{
  $token = $_GET['token'];
  $query = 'select userid,showcustom,template from invoiceshare where invoiceid=? and token=?';
  $query_prm = array($_GET['invoiceid'], $token);
  require('inc/doquery.php');
  if ($num_results)
  {
    $ok = 1;
    $_SESSION['ds_userid'] = $query_result[0]['userid'];
    $_POST['showcustom'] = $query_result[0]['showcustom'];
    $_POST['template'] = $query_result[0]['template'];
    require('inc/setaccess.php');
  }
  else
  {
    $ok = 0;
  }
}
if ($ok == 0) { require('logout.php'); exit; }

$printwindow = 1; # todo remove/fix

$report = '';
if (isset($_POST['report'])) { $report = $_POST['report']; }
if ($report == "" && isset($_GET['report'])) { $report = $_GET['report']; }
$report = d_safebasename($report);
$id = 0; if (isset($_GET['id'])) { $_GET['id'] + 0; } # ???
ob_start();
?>
<!doctype html>
<html>
<head>
<meta http-equiv=content-type content="text/html; charset=UTF-8">
<link rel="icon" href="pics/temico.png" type="image/png">  
</head>
<body>
<?php
function showtitle($title)
{
  echo '<TITLE>'.d_output($_SESSION['ds_customname']).' ' . $title . '</TITLE>';
}

if ($report != "") 
{ 
	require ('printwindow/' . $report . '.php'); 
}

require('printwindow/style_print.php');

require ('inc/bottom.php');

if ($logmeout == 1)
{
  session_unset();
  session_destroy();
}

?>
