<?php
if (!isset($clientA))
{
  $query = 'select clientid,clientname,deleted from client order by deleted,clientname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['clientid']+0);
    $clientA[$id_temp] = d_decode($query_result[$kladd_i]['clientname']);
    $client_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>