<?php
# load $employeedepartmentA[$employeedepartmentid]
if (!isset($employeedepartmentA))
{
  $query = 'select * from employeedepartment';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by deleted,employeedepartmentname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $employeedepartmentid_temp = (int) ($query_result[$kladd_i]['employeedepartmentid']+0);
    $employeedepartmentA[$employeedepartmentid_temp] = $query_result[$kladd_i]['employeedepartmentname'];
    $employeedepartment_deletedA[$employeedepartmentid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
unset($employeedepartmentid_temp);
?>