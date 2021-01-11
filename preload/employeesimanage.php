<?php
# load $employeeA[$employeeid] $exceptme
if (!isset($employeeimanageA))
{
	
  require('preload/employeesorted.php');
  $nbemployees_temp = count($employeesortedA);
  if (!isset($exceptme)) { $exceptme = 0;}

  $ds_isemployeereferencesort = $_SESSION['ds_isemployeereferencesort'] +0 ; 
  $ds_myemployeeid = $_SESSION['ds_myemployeeid'] +0;
  $ds_userid = $_SESSION['ds_userid'];
  
  if($ds_myemployeeid == 0){ $ds_myemployeeid = $ds_userid;}
  $ismanager_temp = $employeesorted_ismanagerA[$ds_myemployeeid] +0;
  $myemployeedepartmentid = $employeesorted_employeedepartmentidA[$ds_myemployeeid];
  $myemployeesectionid = $employeesorted_employeedepartmentidA[$ds_myemployeeid];

  if(isset($employeesortedA))
  {
    foreach($employeesortedA as $employeeid_temp=>$employeename_temp)
    {
      $isemployeemanager_temp = $employeesorted_ismanagerA[$employeeid_temp];
      $employeedepartmentid = $employeesorted_employeedepartmentidA[$employeeid_temp];
      $employeesectionid = $employeesorted_employeedepartmentidA[$employeeid_temp];  
  
      if ($ds_ishrsuperuser
          || ($ismanager_temp && ($employeedepartmentid == $myemployeedepartmentid))
          || ($ismanager_temp && ($employeedepartmentid == $myemployeedepartmentid) && ($employeesectionid == $myemployeesectionid))
          || (($employeeid_temp == $ds_myemployeeid) && ($exceptme == 0)))
      {
        $employeename_temp = '';
        $employeeimanage_referencenumberA[$employeeid_temp] = $employeesorted_referencenumberA[$employeeid_temp];
        $employeeimanage_badgenumberA[$employeeid_temp] = $employeesorted_badgenumberA[$employeeid_temp];
        $employeeimanageA[$employeeid_temp] = $employeesortedA[$employeeid_temp];
        $employeeimanage_lastnameA[$employeeid_temp] = $employeesorted_lastnameA[$employeeid_temp];
        $employeeimanage_firstnameA[$employeeid_temp] = $employeesorted_firstnameA[$employeeid_temp];
        $employeeimanage_middlenameA[$employeeid_temp] = $employeesorted_middlenameA[$employeeid_temp];
        $employeeimanage_iscashierA[$employeeid_temp] = $employeesorted_iscashierA[$employeeid_temp];
        $employeeimanage_issalesA[$employeeid_temp] = $employeesorted_issalesA[$employeeid_temp];
        $employeeimanage_jobidA[$employeeid_temp] = $employeesorted_jobidA[$employeeid_temp];
        $employeeimanage_contractidA[$employeeid_temp] = $employeesorted_contractidA[$employeeid_temp];
        $employeeimanage_categoryidA[$employeeid_temp] = $employeesorted_categoryidA[$employeeid_temp];
        $employeeimanage_deletedA[$employeeid_temp] = $employeesorted_deletedA[$employeeid_temp];
        $employeeimanage_scheduleidA[$employeeid_temp] = $employeesorted_scheduleidA[$employeeid_temp];
        $employeeimanage_ismanagerA[$employeeid_temp] = $ismanager_temp;
        $employeeimanage_employeedepartmentidA[$employeeid_temp] = $employeesorted_employeedepartmentidA[$employeeid_temp];
        $employeeimanage_employeesectionA[$employeeid_temp] = $employeesorted_employeesectionidA[$employeeid_temp];     
        $employeeimanage_employeeemailA[$employeeid_temp] = $employeesorted_employeeemailA[$employeeid_temp];     
        $employeeimanage_hiringdateA[$employeeid_temp] = $employeesorted_hiringdateA[$employeeid_temp]; 
      }
    }
  }

  unset($employeeid_temp,$employeename_temp,$isemployeemanager_temp,$ismanager_temp);
}
?>