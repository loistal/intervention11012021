<?php
# load $warehouseA[$warehouseid]
if (!isset($warehouseA))
{
  $query = 'select warehouseid,warehousename from warehouse order by warehousename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $warehouseid_temp = (int) ($query_result[$kladd_i]['warehouseid']+0);
    $warehouseA[$warehouseid_temp] = $query_result[$kladd_i]['warehousename'];
  }
}
?>