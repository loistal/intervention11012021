<?php
# load $islandA[$islandid]
if (!isset($islandA))
{
  $query = 'select islandid,islandname,regulationzoneid,freightzoneid,outerisland from island order by islandname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $islandid_temp = (int) ($query_result[$kladd_i]['islandid']+0);
    $islandA[$islandid_temp] = $query_result[$kladd_i]['islandname'];
    $island_regulationzoneidA[$islandid_temp] = $query_result[$kladd_i]['regulationzoneid'];
    $island_freightzoneidA[$islandid_temp] = $query_result[$kladd_i]['freightzoneid'];
    $island_outerislandA[$islandid_temp] = $query_result[$kladd_i]['outerisland'];
  }
}
unset($islandid_temp);
?>