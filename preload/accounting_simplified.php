<?php
if (!isset($accounting_simplifiedA))
{
  $query = 'select accounting_simplifiedid,accounting_simplifiedname,accounting_simplifiedgroupid,deleted from accounting_simplified order by deleted,accounting_simplifiedname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['accounting_simplifiedid']+0);
    $accounting_simplifiedA[$id_temp] = $query_result[$kladd_i]['accounting_simplifiedname'];
    $accounting_simplifiedgroupidA[$id_temp] = $query_result[$kladd_i]['accounting_simplifiedgroupid'];
    $accounting_simplified_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>