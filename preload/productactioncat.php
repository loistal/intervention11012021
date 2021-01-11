<?php
if (!isset($productactioncatA))
{
  $query = 'select productactioncatid,productactioncatname,deleted from productactioncat order by deleted,productactioncatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['productactioncatid']+0);
    $productactioncatA[$temp_id] = $query_result[$kladd_i]['productactioncatname'];
    $productactioncat_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
unset($temp_id, $kladd_i);
?>