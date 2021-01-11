<?php
# load $placementA[$placementid]
if (!isset($placementA))
{
  $query = 'select * from placement order by deleted,placementrank,placementname,placementid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $placementid_temp = (int) ($query_result[$kladd_i]['placementid']+0);
    $placementA[$placementid_temp] = $query_result[$kladd_i]['placementname'];
    $placement_warehouseidA[$placementid_temp] = $query_result[$kladd_i]['warehouseid'];
    $placement_placementrankA[$placementid_temp] = $query_result[$kladd_i]['placementrank'];
    $placement_useridA[$placementid_temp] = $query_result[$kladd_i]['userid'];
    $placement_counteddateA[$placementid_temp] = $query_result[$kladd_i]['counteddate'];
    $placement_countedtimeA[$placementid_temp] = $query_result[$kladd_i]['countedtime'];
    $placement_creationzoneA[$placementid_temp] = $query_result[$kladd_i]['creationzone']; 
    $placement_pickingzoneA[$placementid_temp] = $query_result[$kladd_i]['pickingzone']; 
    $placement_transportzoneA[$placementid_temp] = $query_result[$kladd_i]['transportzone'];
    $placement_deletionzoneA[$placementid_temp] = $query_result[$kladd_i]['deletionzone'];
    $placement_deletedA[$placementid_temp] = $query_result[$kladd_i]['deleted'];    
  }
}
unset($placementid_temp);
unset($kladd_i);

?>