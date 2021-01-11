<?php

require('preload/taxcode.php');

$ouryear = (int) $_POST['ouryear'];

$pid1 = $_POST['pid1']+0;
$pid2 = $_POST['pid2']+0;

$ouryear = $_POST['ouryear']+0;
$currentyear = $ouryear; $dp_donotupdate = 1;

$query = 'select product.productid,suppliercode,productname,numberperunit,netweightlabel,salesprice,taxcodeid,stock from product,endofyearstock where endofyearstock.productid=product.productid and product.productid>=? and product.productid<=? and year=?';
$query_prm = array($pid1,$pid2,$ouryear); #$query .= ' limit 10';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

$ourtitle = 'Stock Fin Année ' . $ouryear;
showtitle($ourtitle);
echo '<h2>' .$ourtitle. '</h2>';
echo 'code;designation;quantité 31/12;Prix TTC;Prix de revient<br>';

for ($i=0;$i<$num_results_main;$i++)
{
  if ($_SESSION['ds_useproductcode']) { echo d_output($main_result[$i]['suppliercode']); }
  else { echo $main_result[$i]['productid']; }
  echo ';' . d_output(d_decode($main_result[$i]['productname']));
  if ($main_result[$i]['numberperunit'] > 1) { echo ' ' . $main_result[$i]['numberperunit'] . ' x ' . d_output($main_result[$i]['netweightlabel']); }
  echo ';';
  
  /*
  $numberperunit = $main_result[$i]['numberperunit'];
  $productid = $main_result[$i]['productid'];
  require('inc/calcstock.php');
  echo $currentstock . ';';
  */
  echo floor($main_result[$i]['stock'] / $main_result[$i]['numberperunit']) . ';';
  
  
  echo myround(($main_result[$i]['salesprice'] + ($main_result[$i]['salesprice'] * ($taxcodeA[$main_result[$i]['taxcodeid']]/100))),0) . ';';

  $query = 'SELECT prev FROM purchasebatch where productid=? order by arrivaldate desc LIMIT 1';
  $query_prm = array($main_result[$i]['productid']);
  require('inc/doquery.php');
  echo $query_result[0]['prev']+0;

  echo '<br>';
}

?>