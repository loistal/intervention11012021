<?php
if (!isset($teamA))
{
  $query = 'select teamid,teamname,deleted from team order by deleted,teamname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['teamid']+0);
    $teamA[$temp_id] = $query_result[$kladd_i]['teamname'];
    $team_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
unset($temp_id, $kladd_i);
?>