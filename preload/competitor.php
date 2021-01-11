<?php
if (!isset($competitorA))
{
  $query = 'select competitorid,competitorname,deleted from competitor order by deleted,competitorname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['competitorid']+0);
    $competitorA[$temp_id] = $query_result[$kladd_i]['competitorname'];
    $competitor_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
unset($temp_id, $kladd_i);
?>