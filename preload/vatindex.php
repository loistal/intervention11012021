<?php
if (!isset($vatindexA))
{
  $query = 'select * from vatindex order by vatindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['vatindexid']+0);
    $vatindexA[$id_temp] = $query_result[$kladd_i]['vatindexname'];
  }
}
?>