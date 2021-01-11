<?php

$query = 'select modifiedstockid,purchasebatch.productid from modifiedstock,purchasebatch where modifiedstock.purchasebatchid=purchasebatch.purchasebatchid and modifiedstock.productid=0 limit 100';
$query_prm = array();
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  echo $main_result[$i]['modifiedstockid'] . ' ' . $main_result[$i]['productid'] . '<br>';
  $query = 'update modifiedstock set productid=? where modifiedstockid=?';
  $query_prm = array($main_result[$i]['productid'],$main_result[$i]['modifiedstockid']);
  require ('inc/doquery.php');
}
echo $num_results_main . ' Done.';

?>