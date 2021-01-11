<?php #get all employees to display
require ('preload/employee.php');
require ('preload/employeedepartment.php');
require ('preload/employeesection.php');

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$ds_isemployeereferencesort = $_SESSION['ds_isemployeereferencesort'] +0;
if (!isset($dp_employeecategoryid)) { $dp_employeecategoryid = 0;}

$query = 'select * from employee where deleted=0';
$query_prm = array();
if ($dp_employeecategoryid > 0)
{
	$query .= ' and employeecategoryid = ? ';
	array_push($query_prm,$dp_employeecategoryid);
}

$isrequestnecessary = 1;

if (!isset($ourparams)) { $ourparams = '';}

if ($employeeid == $ALL)
{
  require ('preload/employeesortedbyteam.php');
  $e = 0;
  foreach($employeesortedbyteamA as $sid=>$sname)
  {
		$catid = $employeesortedbyteam_categoryidA[$sid];
		if (($dp_employeecategoryid == 0) || ($dp_employeecategoryid > 0 && $catid == $dp_employeecategoryid))
		{
			$employee_todisplayA[$e]['employeeid'] = $sid;
			$e++;
		}
  }
  $nbemployees = $e;
  $isrequestnecessary = 0;  
}
else if ($employeeid == $MYTEAM) # employees from the same team
{
  $query .= ' and employeedepartmentid = ? and employeesectionid = ?';
  array_push($query_prm,$myemployeedepartmentid,$myemployeesectionid);
  #so I am not considered as a manager in this team but as an employee
  $ismanager = 0;

  if ($myemployeedepartmentid > 0) { $ourparams .= '<p>' . $employeedepartmentA[$myemployeedepartmentid];}
  if ($myemployeesectionid > 0) { $ourparams .= '/' . $employeesectionA[$myemployeesectionid];}  
  $ourparams .= '</p>';    
}
elseif ($employeeid == $TEAMIMANAGE) 
{  

  if (($myemployeedepartmentid == 0) && ($myemployeesectionid == 0))  
  {
    # I am director: my team is departmentmanager
    $query .= ' and employeeid=? or (employeesectionid = 0 and ismanager=1)';
    array_push($query_prm,$ds_myemployeeid);
  }  
  elseif (($myemployeedepartmentid > 0) && ($myemployeesectionid == 0))  
  {
    # I am department manager  
    $query .= ' and employeedepartmentid = ?';
    array_push($query_prm,$myemployeedepartmentid);
  }
  else if ($myemployeesectionid > 0)  
  {
    # I am sectionmanager
    $query .= ' and employeesectionid = ?';
    array_push($query_prm,$myemployeesectionid);
  }
  
  if ($myemployeedepartmentid > 0) { $ourparams .= '<p>' . $employeedepartmentA[$myemployeedepartmentid];}
  if ($myemployeesectionid > 0) { $ourparams .= '/' . $employeesectionA[$myemployeesectionid];}
  $ourparams .= '</p>';  
}
elseif ($employeeid < 0) 
{  
  # employees from another team
  #delete the "-" before employeeid_save and split departmentid and sectionid
  $posunderscore = mb_stripos($employeeid,'_');
  $len = mb_strlen($employeeid);
  $employeedepartmentid = mb_substr($employeeid,1,$posunderscore-1);
  $employeesectionid = mb_substr($employeeid,$posunderscore + 1,$len);
  $query .= ' and employeedepartmentid = ? and employeesectionid=?';
  array_push($query_prm,$employeedepartmentid,$employeesectionid);

  if ($employeedepartmentid > 0) { $ourparams .= '<p>' . $employeedepartmentA[$employeedepartmentid];}
  if ($employeesectionid > 0) { $ourparams .= '/' . $employeesectionA[$employeesectionid];}  
  $ourparams .= '</p>';
} 
else
{
  $nbemployees = 1;
  $employee_todisplayA[0]['employeeid'] = $employeeid;
  $isrequestnecessary = 0;  
  $ourparams .= '<p>'. $employeeA[$employeeid] .'</p>' ; 
}  
if($isrequestnecessary == 1)
{
  $query .= ' order by';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= ' referencenumber';   
  }
  else
  {
    $query .= ' ismanager desc,employeename'; 
  }
  require('inc/doquery.php');
  $employee_todisplayA = $query_result;
  $nbemployees = $num_results;
  unset($query_result,$num_results);
}

?>