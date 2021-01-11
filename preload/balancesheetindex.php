<?php
if (!isset($balancesheetindexA))
{
  $query = 'select * from balancesheetindex order by balancesheetindexname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = $query_result[$kladd_i]['balancesheetindexid']; # not an int
    $balancesheetindexA[$id_temp] = $query_result[$kladd_i]['balancesheetindexname'];
  }
}
?>