<?php
if (!isset($absence_reasonA))
{
  $query = 'select absence_reasonid,absence_reasonname,deleted from absence_reason order by deleted,absence_reasonname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['absence_reasonid']+0);
    $absence_reasonA[$temp_id] = $query_result[$kladd_i]['absence_reasonname'];
    $absence_reason_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>