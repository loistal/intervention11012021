<?php
if (!isset($weeklyhoursA))
{
  $query = 'select * from weeklyhours order by weeklyhoursname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['weeklyhoursid']+0);
    $weeklyhoursA[$id_temp] = $query_result[$kladd_i]['weeklyhoursname'];
    $weeklyhours_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>