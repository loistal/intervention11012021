<?php
# load $companytransportA[$companytransportid]
if (!isset($companytransportA))
{
  $query = 'select companytransportid,companytransportname,deleted from companytransport order by deleted,companytransportname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $companytransportid_temp = (int) ($query_result[$kladd_i]['companytransportid']+0);
    $companytransportA[$companytransportid_temp] = $query_result[$kladd_i]['companytransportname'];
    $companytransport_deletedA[$companytransportid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>