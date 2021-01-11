<?php

require ('inc/standard.php');

if (isset($_POST['report'])) { $report = $_POST['report']; }
elseif (isset($_GET['report'])) { $report = $_GET['report']; }
else { $report = ''; }
$report = d_safebasename($report);
if ($report == 'ca1') { $report = 'ca'; $page = 1; }
if ($report == 'ca2') { $report = 'ca'; $page = 2; }
if ($report != "") { require ('declaration/' . $report . '.php'); }

?>
