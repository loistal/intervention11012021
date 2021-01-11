<?php

$PA['filter'] = '';
$PA['client'] = 'uint';
require('inc/readpost.php');

session_write_close();

require("phpqrcode/qrlib.php");
$errorCorrectionLevel = 'L'; # array('L','M','Q','H')
$matrixPointSize = 4; # 1 to 10
$PNG_TEMP_DIR = 'customfiles/';
$PNG_WEB_DIR = 'customfiles/';

$title = 'Sites QR';
showtitle($title);

$query_prm = array();
$query = 'select qr_locationid,qr_locationname from qr_location
          where deleted=0';
if ($filter != '') { $query .= ' and qr_locationname like ?'; array_push($query_prm, '%'.$filter.'%'); }
if ($client > 0) { $query .= ' and clientid=?'; array_push($query_prm, $client); }
$query .= ' order by qr_locationname';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{  
  echo '<div style="width: 500">'; # TODO style

  $qr_locationid = $main_result[$i]['qr_locationid'];
  $url = 'http://' . $_SERVER['SERVER_NAME'] . '/locationqrscan.php?qr_locationid='.$qr_locationid;

  $filename = $PNG_TEMP_DIR.'qr_location'.md5($url.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';

  QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

  echo '<table class="transparent"><tr><td><img src="'.$PNG_WEB_DIR.basename($filename).'">';
  echo '<td valign=top><br><span style="font-size: 15pt">';
  echo d_output($main_result[$i]['qr_locationname']);
  echo '</span></table></div><br><br>';
}
?>