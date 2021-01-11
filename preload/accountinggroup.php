<?php
if (!isset($accountinggroupA))
{
  $query = 'select * from accountinggroup order by agname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['accountinggroupid']+0);
    $accountinggroupA[$id_temp] = $query_result[$kladd_i]['agname'];
  }
}
?>