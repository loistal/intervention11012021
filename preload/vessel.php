<?php
if (!isset($vesselA))
{
  $query = 'select vesselid,vesselname,deleted from vessel order by deleted,vesselname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $vesselid_temp = (int) ($query_result[$kladd_i]['vesselid']+0);
    $vesselA[$vesselid_temp] = $query_result[$kladd_i]['vesselname'];
    $vessel_deletedA[$vesselid_temp] = $query_result[$kladd_i]['deleted'];
  }
  unset($vesselid_temp,$kladd_i);
}
?>