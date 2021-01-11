<?php
if (!isset($turnoverindexA))
{
  $query = 'select * from turnoverindex order by turnoverindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['turnoverindexid']+0);
    $turnoverindexA[$id_temp] = $query_result[$kladd_i]['turnoverindexname'];
  }
}
?>