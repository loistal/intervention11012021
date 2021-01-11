<?php
# load $bankA[$bankid]
if (!isset($socialsecuritysectorA))
{
  $query = 'select * from socialsecuritysector order by deleted,socialsecuritysectorname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['socialsecuritysectorid']+0);
    $socialsecuritysectorA[$id_temp] = $query_result[$kladd_i]['socialsecuritysectorname'];
    $socialsecuritysector_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>