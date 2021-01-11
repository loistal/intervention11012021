<?php
# load $employeecategoryA[$employeecategoryid]
if (!isset($employeecategoryA))
{
  $query = 'select * from employeecategory order by deleted,employeecategoryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $employeecategoryid_temp = (int) ($query_result[$kladd_i]['employeecategoryid']+0);
    $employeecategoryA[$employeecategoryid_temp] = $query_result[$kladd_i]['employeecategoryname'];
    //$employeecategory_numdailycheckingA[$employeecategoryid_temp] = $query_result[$kladd_i]['numdailychecking'];
    $employeecategory_deletedA[$employeecategoryid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
unset($employeecategoryid_temp);
?>