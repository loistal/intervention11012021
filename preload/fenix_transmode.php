<?php
if (!isset($fenix_transmodeA))
{
  $query = 'select fenix_transmodeid,transmodename,deleted from fenix_transmode order by deleted,fenix_transmodeid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['fenix_transmodeid']+0);
    $fenix_transmodeA[$temp_id] = $query_result[$kladd_i]['transmodename'];
    $fenix_transmode_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>