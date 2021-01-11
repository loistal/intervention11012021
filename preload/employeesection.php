<?php
# load $employeesectionA[$employeesectionid]
require('preload/employeedepartment.php');
if (!isset($employeesectionA))
{
  $query = 'select * from employeesection';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by deleted,employeesectionname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $employeesectionid_temp = (int) ($query_result[$kladd_i]['employeesectionid']+0);
    $employeedepartmentid = $employeesection_employeedepartmentidA[$employeesectionid_temp] = $query_result[$kladd_i]['employeedepartmentid'];    
    $employeesectionA[$employeesectionid_temp] = $query_result[$kladd_i]['employeesectionname'];
    $employeedepartmentname = $employeedepartmentA[$employeedepartmentid];
    if (isset($employeedepartmentname)) { $employeedepartmentsectionA[$employeesectionid_temp] = $employeedepartmentname . '/'; }
    $employeedepartmentsectionA[$employeesectionid_temp] .= $query_result[$kladd_i]['employeesectionname'];
    $employeesection_deletedA[$employeesectionid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
unset($employeesectionid_temp);
?>