<?php
# load $resourceA[$resourceid]
if (!isset($resourceA))
{
  $query = 'select resourceid,resourcename,deleted from resource order by resourcename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $resourceid_temp = (int) ($query_result[$kladd_i]['resourceid']+0);
    $resourceA[$resourceid_temp] = $query_result[$kladd_i]['resourcename'];
    $resource_deletedA[$resourceid_temp] = $query_result[$kladd_i]['deleted'];    
  }
}
unset($resourceid_temp);
?>