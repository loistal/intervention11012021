<?php

$PA['min'] = 'uint';
$PA['max'] = 'uint';
$PA['showlogo'] = 'uint';
require('inc/readpost.php');

session_write_close();

require("phpqrcode/qrlib.php");
$errorCorrectionLevel = 'L'; # array('L','M','Q','H')
$matrixPointSize = 4; # 1 to 10
$PNG_TEMP_DIR = 'customfiles/';
$PNG_WEB_DIR = 'customfiles/';

$title = 'Badges QR pour employé(e)s';
showtitle($title);

$query_prm = array();
$query = 'select employee.employeeid,employeename,employeefirstname,photoid from employee,employeepersoinfos
          where employee.employeeid=employeepersoinfos.employeeid and employee.deleted=0';
if ($min > 0) { $query .= ' and employee.employeeid>=?'; array_push($query_prm, $min); }
if ($max > 0) { $query .= ' and employee.employeeid<=?'; array_push($query_prm, $max); }
$query .= ' order by employeeid';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{  
  echo '<div style="width: 500">'; # TODO style

  $employeeid = $main_result[$i]['employeeid'];
  $url = 'http://' . $_SERVER['SERVER_NAME'] . '/employeeqrscan.php?employeeid='.$employeeid;

  $filename = $PNG_TEMP_DIR.'qrbadge'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';

  QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

  echo '<table class="transparent"><tr><td><img src="'.$PNG_WEB_DIR.basename($filename).'">';
  if ($showlogo && isset($_SESSION['ds_customname']) && $_SESSION['ds_customname'] != "")
  {
    $ourlogofile = './custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
    if (file_exists($ourlogofile)) { echo '<td><img alt="' . $_SESSION['ds_customname'] . '" src="' . $ourlogofile . '" border=0 style="max-height: 150px;">&nbsp;'; }
  }
  if ($main_result[$i]['photoid'])
  {
    echo '<td><img style="max-height:150px;" src="viewimage.php?image_id=' . $main_result[$i]['photoid'] . '">';
  }
  echo '<td valign=top><br><span style="font-size: 15pt">';
  echo d_output($main_result[$i]['employeename']) . ' ' . d_output($main_result[$i]['employeefirstname']);
  echo '</span></table></div><br><br>';
}
?>