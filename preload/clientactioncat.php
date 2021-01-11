<?php
# load $clientactioncatA[$clientactioncatid]
if (!isset($clientactioncatA))
{
  $query = 'select clientactioncatid,clientactioncatname,deleted from clientactioncat order by deleted,clientactioncatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $clientactioncatid_temp = (int) ($query_result[$kladd_i]['clientactioncatid']+0);
    $clientactioncatA[$clientactioncatid_temp] = $query_result[$kladd_i]['clientactioncatname'];
    $clientactioncat_deletedA[$clientactioncatid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>