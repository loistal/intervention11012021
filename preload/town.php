<?php
# load $townA[$townid] and $town_islandidA[$townid]
if (!isset($townA))
{
  $query = 'select townid,townname,islandid from town order by islandid,townname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $townid_temp = (int) ($query_result[$kladd_i]['townid']+0);
    $townA[$townid_temp] = $query_result[$kladd_i]['townname'];
    $town_islandidA[$townid_temp] = $query_result[$kladd_i]['islandid'];
  }
}
?>