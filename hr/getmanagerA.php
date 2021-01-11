<?php 
#input $hr_tointerimmanager if interim manager is asked to manage instead of manager
require('preload/employeesorted.php');

if(!isset($hr_tointerimmanager)){$hr_tointerimmanager = 0;}
$ismanager_temp = $employeesorted_ismanagerA[$employeeid];
$interimmanagerid_temp = $employeesorted_interimmanageridA[$employeeid];
$employeedepartmentid_temp = $employeesorted_employeedepartmentidA[$employeeid];
$employeesectionid_temp = $employeesorted_employeesectionidA[$employeeid];
#if manager is a superuser without myemployeeid
$managerisonlyuser = 0;
$managernotfound = 1;
if ($ismanager_temp == 0)
{
  #if this employee is not manager, get his manager(s)
  $query = 'select * from employee where deleted = 0 and ismanager = 1 and employeedepartmentid=? and employeesectionid=?';
  $query_prm = array($employeedepartmentid_temp,$employeesectionid_temp);
  require('inc/doquery.php');  
  if ($num_results > 0) { $managerA_temp = $query_result;$managernotfound = 0;}
}  

if ($managernotfound || ($ismanager_temp && $employeesectionid_temp > 0))
{
  #get department manager(s)
  $query = 'select * from employee where deleted = 0 and ismanager = 1 and employeedepartmentid=? and employeesectionid=0';
  $query_prm = array($employeedepartmentid_temp);   
  require('inc/doquery.php');  
  if ($num_results > 0) { $managerA_temp = $query_result;$managernotfound = 0;}
}

if ($managernotfound || ($ismanager_temp && $employeesectionid_temp == 0))
{
  #get supermanager(s)
  $query = 'select * from employee where deleted = 0 and ismanager = 1 and employeedepartmentid=0';
  $query_prm = array();  
  require('inc/doquery.php');  
  if ($num_results > 0) { $managerA_temp = $query_result;$managernotfound = 0;}
}

if($managernotfound == 0) 
{
  if ($hr_tointerimmanager == 1) 
  {
    # if absence asked to interim manager: get interim manager (s) info
    # ifinterim manager not found => as if manager was not found
    for($mm=0;$mm<count($managerA_temp);$mm++)
    {
      $mid = $managerA_temp[$mm]['employeeid'];
      $interimid= $employeesorted_interimmanageridA[$mid];
      if ($interimid > 0)
      {
        $managerA[$mm]['employeeid'] = $interimmid;
        $managerA[$mm]['employeename'] = $employeesortedA[$interimid];
        $managerA[$mm]['employeeemail'] = $employeesorted_employeeemailA[$interimid];
      }
      else
      {
        $managernotfound = 1;
      }
    }
  }
  else
  {
    $managerA = $managerA_temp;
  }
}

if ($managernotfound)  
{
  #get superuser employeeemail
  $query = 'select * from employee e, usertable u where e.deleted = 0 and u.deleted = 0 and u.ishrsuperuser = 1 and e.employeeid = u.myemployeeid';
  $query_prm = array();       

  require('inc/doquery.php');  
  if ($num_results > 0) 
  { 
    $managerA = $query_result;
    $managernotfound = 0;
  }  
  else
  {
    #get superuser useremail
    $query = 'select * from usertable u where u.deleted = 0 and u.ishrsuperuser = 1';
    $query_prm = array();  
    $managerisonlyuser = 1;
    require('inc/doquery.php');  
    if ($num_results > 0) { $managerA = $query_result;}  
  }

}
unset($hr_tointerimmanager,$managerA_temp);
?>    