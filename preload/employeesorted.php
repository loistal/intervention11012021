<?php
# load $employeesortedA[$employeeid]
# if $ds_isemployeereferencesort == 1 => sort by reference number_format and concat reference number and name 

if (!isset($employeesortedA))
{
  $num_employees = 0;
  $ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser'];

  $ds_isemployeereferencesort = $_SESSION['ds_isemployeereferencesort'] + 0; 

  if($ds_isemployeereferencesort == 1)
  {
    $query = 'select * from employee where deleted = 0'; 
    $query .= ' order by referencenumber';
    $query_prm = array();
    require('inc/doquery.php');
    $employeedisplayedA = $query_result;
    $num_employees = $num_results;
    for ($kladd_i=0;$kladd_i<$num_employees;$kladd_i++)
    {
      $employeeid_temp = (int) ($employeedisplayedA[$kladd_i]['employeeid']);
      $employeename_temp = '';
      $referencenumber = $employeesorted_referencenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['referencenumber'];
      $badgenumber = $employeesorted_badgenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['badgenumber'];
      if(isset($referencenumber) && $referencenumber != ''){$employeename_temp = $referencenumber . '_';}
			
			$employeename = $employeesorted_lastnameA[$employeeid_temp] = $employeename_temp . $employeedisplayedA[$kladd_i]['employeename'];		
			$employeefirstname = $employeesorted_firstnameA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeefirstname'];     
			$employeesorted_middlenameA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeemiddlename'];      

			if (!empty($employeename)) { $employeename .= ' ';}
			if (!empty($employeefirstname)) { $employeename .= $employeefirstname;}		
			
      if ($employeedisplayedA[$kladd_i]['deleted'] == 1) { $employeesortedA[$employeeid_temp] = d_trad ('deletedsquarebrackets',$employeename); } 
			$employeesortedA[$employeeid_temp] = $employeename;
      $employeesorted_iscashierA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['iscashier'];
      $employeesorted_issalesA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['issales'];
      $employeesorted_jobidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['jobid'];
      $employeesorted_contractidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['contractid'];
      $employeesorted_categoryidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeecategoryid'];
      $employeesorted_deletedA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['deleted'];
      $employeesorted_scheduleidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['scheduleid'];
      $employeesorted_ismanagerA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['ismanager'];
      $employeesorted_interimmanageridA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['interimmanagerid'];
      $employeesorted_employeedepartmentidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeedepartmentid'];
      $employeesorted_employeesectionidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeesectionid'];  
      $employeesorted_employeeemailA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeeemail'];  
      $employeesorted_hiringdateA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['hiringdate'];  
    }  
    unset($employeeid_temp);    
  }
  else
  {
    require('preload/employeesortedbyteam.php');
    $employeesortedA = $employeesortedbyteamA;
    $employeesorted_lastnameA = $employeesortedbyteam_lastnameA;
    $employeesorted_firstnameA = $employeesortedbyteam_firstnameA;
    $employeesorted_middlenameA = $employeesortedbyteam_middlenameA;  	
    $employeesorted_referencenumberA = $employeesortedbyteam_referencenumberA;
    $employeesorted_badgenumberA = $employeesortedbyteam_badgenumberA;  
    $employeesorted_iscashierA = $employeesortedbyteam_iscashierA;
    $employeesorted_issalesA = $employeesortedbyteam_issalesA;
    $employeesorted_jobidA = $employeesortedbyteam_jobidA;
    $employeesorted_contractidA = $employeesortedbyteam_contractidA;
    $employeesorted_categoryidA = $employeesortedbyteam_categoryidA;
    $employeesorted_deletedA = $employeesortedbyteam_deletedA;
    $employeesorted_scheduleidA = $employeesortedbyteam_scheduleidA;
    $employeesorted_ismanagerA = $employeesortedbyteam_ismanagerA;
    $employeesorted_interimmanageridA = $employeesortedbyteam_interimmanageridA;
    $employeesorted_employeedepartmentidA = $employeesortedbyteam_employeedepartmentidA;
    $employeesorted_employeesectionidA = $employeesortedbyteam_employeesectionidA; 
    $employeesorted_employeeemailA = $employeesortedbyteam_employeeemailA; 
    $employeesorted_hiringdateA = $employeesortedbyteam_hiringdateA;      
  }
  $num_employees = count($employeesortedA);
}
?>