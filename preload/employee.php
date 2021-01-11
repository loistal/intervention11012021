<?php
# TODO clean up this mess, refactor like other preloads

if (!isset($employeeA))
{
  $num_employees = 0;

  $query = 'select * from employee';
  if ($_SESSION['ds_employeenamedisplay'] == 0)
  {
    $query .= ' order by deleted,employeefirstname,employeename,employeemiddlename';
  }
  else
  {
    $query .= ' order by deleted,employeename,employeefirstname,employeemiddlename';
  }
  $query_prm = array();
  require('inc/doquery.php');
  $employeedisplayedA = $query_result;$num_employees = $num_results;unset($query,$query_result,$num_results);   

  for ($kladd_i=0;$kladd_i<$num_employees;$kladd_i++)
  {
    $employeeid_temp = (int) ($employeedisplayedA[$kladd_i]['employeeid']+0);
    $employee_firstnameA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeefirstname'];
    $employee_middlenameA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeemiddlename'];
    $employee_lastnameA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeename'];

    if ($_SESSION['ds_employeenamedisplay'] == 0)
    {
      $employeeA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeename'];
      if ($employee_firstnameA[$employeeid_temp] != '') { $employeeA[$employeeid_temp] = $employee_firstnameA[$employeeid_temp] . ' ' . $employeeA[$employeeid_temp]; }
    }
    else
    {
      $employeeA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeename'];
      if ($employee_firstnameA[$employeeid_temp] != '') { $employeeA[$employeeid_temp] .= ', ' . $employee_firstnameA[$employeeid_temp]; }
    }

    if ($employeedisplayedA[$kladd_i]['deleted'] == 1) { $employeeA[$employeeid_temp] = d_trad ('deletedsquarebrackets',$employeeA[$employeeid_temp]); } 
    $employee_iscashierA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['iscashier'];
    $employee_issalesA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['issales'];
    $employee_isdeliveryA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['isdelivery'];
    $employee_ispickingA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['ispicking'];
    $employee_jobidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['jobid'];
    $employee_contractidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['contractid'];
    $employee_categoryidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeecategoryid'];
    $employee_deletedA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['deleted'];
    $employee_scheduleidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['scheduleid'];
    $employee_ismanagerA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['ismanager'];
    $employee_interimmanageridA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['interimmanagerid'];
    $employee_referencenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['referencenumber'];
    $employee_badgenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['badgenumber'];
    $employee_employeedepartmentidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeedepartmentid'];
    $employee_employeesectionidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeesectionid'];
    $employee_employeeemailA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeeemail'];
    $employee_hiringdateA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['hiringdate'];
    $employee_teamidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['teamid'];
  }
  unset($employeeid_temp);
}
?>