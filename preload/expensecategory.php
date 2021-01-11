<?php
# load $expensecategoryA[$expensecategoryid]
if (!isset($expensecategoryA))
{
  $query = 'select expensecatid,expensecatname from expensecat order by expensecatname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $expensecategoryid = (int) ($query_result[$kladd_i]['expensecatid']+0);
    $expensecategoryA[$expensecategoryid] = $query_result[$kladd_i]['expensecatname'];
  }
}
?>