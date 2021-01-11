<?php

if (($_SESSION['ds_userid']+0) < 1 && $_SESSION['ds_clientaccess'] < 1)
{
  require('logout.php'); exit;
}

require ('inc/standard.php');
session_write_close(); # TODO move this into individual reports AFTER all d_trad

# reserved variables
$trcolor = 0; # TODO remove

require ('inc/top.php');

function showtitle($title) # TODO remove
{
  echo '<TITLE>'.d_output($_SESSION['ds_customname']).' ' . $title . '</TITLE>';
}

function showtitle_new($title) # TODO each report MUST use showtitle()
{
  echo '<title>'.d_output($_SESSION['ds_customname']).' ',d_output($title),'</title><h2>',$title,'</h2>';
}

if (isset($_POST['report'])) { $report = $_POST['report']; }
else { $report = ""; }
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }
$report = d_safebasename($report);

if ($report != "") { require ('reportwindow/' . $report . '.php'); }

require ('inc/bottom.php');

?>

