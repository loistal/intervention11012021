<?php
# load $clientsectorA[$clientsectorid]
if (!isset($clientsectorA))
{
  $query = 'select clientsectorid,clientsectorname,clientsectorrank,deleted from clientsector order by deleted,clientsectorrank,clientsectorname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientsectorid_temp = (int) ($query_result[$kladd_i]['clientsectorid']+0);
    $clientsectorA[$clientsectorid_temp] = $query_result[$kladd_i]['clientsectorname'];
    $clientsector_rankA[$clientsectorid_temp] = $query_result[$kladd_i]['clientsectorrank'];
    $clientsector_deletedA[$clientsectorid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>