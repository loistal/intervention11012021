<?php
if (!isset($modifiedstockreasonA))
{
  $query = 'select modifiedstockreasonid,modifiedstockreasonname,deleted from modifiedstockreason order by deleted,modifiedstockreasonname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $modifiedstockreasonid_temp = (int) ($query_result[$kladd_i]['modifiedstockreasonid']+0);
    $modifiedstockreasonA[$modifiedstockreasonid_temp] = $query_result[$kladd_i]['modifiedstockreasonname'];
    $modifiedstockreason_deletedA[$modifiedstockreasonid_temp] = $query_result[$kladd_i]['deleted'];
  }
  unset($modifiedstockreasonid_temp,$kladd_i);
}
?>