<?php
# load $clientschedulecatA[$clientschedulecatid]
if (!isset($clientschedulecatA))
{
  $query = 'select clientschedulecatid,clientschedulecatname,deleted from clientschedulecat order by deleted,clientschedulecatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientschedulecatid_temp = (int) ($query_result[$kladd_i]['clientschedulecatid']+0);
    $clientschedulecatA[$clientschedulecatid_temp] = $query_result[$kladd_i]['clientschedulecatname'];
    $clientschedulecat_deletedA[$clientschedulecatid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>