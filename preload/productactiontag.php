<?php
if (!isset($productactiontagA))
{
  $query = 'select productactiontagid,productactiontagname,deleted from productactiontag order by deleted,productactiontagname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['productactiontagid']+0);
    $productactiontagA[$id_temp] = $query_result[$kladd_i]['productactiontagname'];
    $productactiontag_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>