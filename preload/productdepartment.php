<?php
# load $productdepartmentA[$productdepartmentid]
if (!isset($productdepartmentA))
{
  $query = 'select productdepartmentid,productdepartmentname from productdepartment order by departmentrank,productdepartmentname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  { 
    $productdepartmentid_temp = (int) ($query_result[$kladd_i]['productdepartmentid']+0);
    $productdepartmentA[$productdepartmentid_temp] = $query_result[$kladd_i]['productdepartmentname'];
  }
}
unset ($productdepartmentid_temp);
?>