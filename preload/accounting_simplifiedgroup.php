<?php
if (!isset($accounting_simplifiedgroupA))
{
  $query = 'select * from accounting_simplifiedgroup order by deleted,`rank`,accounting_simplifiedgroupname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['accounting_simplifiedgroupid']+0);
    $accounting_simplifiedgroupA[$id_temp] = $query_result[$kladd_i]['accounting_simplifiedgroupname'];
    $accounting_simplifiedgroup_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>