<?php
if (!isset($warehousereasonA))
{
  $query = 'select warehousereasonid,warehousereasonname,deleted from warehousereason order by warehousereasonname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $warehousereasonid_temp = (int) ($query_result[$kladd_i]['warehousereasonid']+0);
    $warehousereasonA[$warehousereasonid_temp] = $query_result[$kladd_i]['warehousereasonname'];
    $warehousereason_deletedA[$warehousereasonid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>