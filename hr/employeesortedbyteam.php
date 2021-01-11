<?php
# load $employeesortedbyteamA[$employeeid]
if (!isset($employeesortedbyteamA))
{
  $num_employees = 0;
  $ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser'];
	$ds_isemployeereferencesort = $_SESSION['ds_isemployeereferencesort'] + 0;

  #NO DEPARTMENT NO SECTION
  #1: managers without department/section
  $query = 'select * from employee where ismanager = 1 and employeedepartmentid=0 and employeesectionid=0 and deleted = 0';
  $query .= ' order by ';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= 'referencenumber';   
  }
  else
  {
    $query .= 'employeename'; 
  }
  $query_prm = array();
  require('inc/doquery.php');
  $managerwithoutdptA = $query_result;$num_managerswithoutdpt = $num_results;unset($query,$query_result,$num_results);    

  #2: employees without department/section
  $query = 'select * from employee where ismanager = 0 and employeedepartmentid=0 and employeesectionid=0 and deleted = 0';
  $query .= ' order by ';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= 'referencenumber';   
  }
  else
  {
    $query .= 'employeename'; 
  }
  $query_prm = array();
  require('inc/doquery.php');
  $employeewithoutdptA = $query_result;$num_employeeswithoutdpt = $num_results; unset($query,$query_result,$num_results);     

  # NO SECTION
  #3: managers with dpt without section
  $query = 'select e.* from employee e, employeedepartment ed where  e.ismanager = 1 and e.employeedepartmentid>0 and e.employeesectionid=0 and e.employeedepartmentid = ed.employeedepartmentid and e.deleted = 0';
  $query .= '  order by ed.employeedepartmentname';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= ',e.referencenumber';   
  }
  else
  {
    $query .= ',e.employeename'; 
  }  
  $query_prm = array();
  require('inc/doquery.php');
  $managerwithoutsecA = $query_result;$num_managerswithoutsec = $num_results;unset($query,$query_result,$num_results);  
   
  #4: employees without department/section
  $query = 'select e.* from employee e, employeedepartment ed where  e.ismanager = 0 and e.employeedepartmentid>0 and e.employeesectionid=0 and e.employeedepartmentid = ed.employeedepartmentid and e.deleted = 0';
  $query .= '  order by ed.employeedepartmentname';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= ',e.referencenumber';   
  }
  else
  {
    $query .= ',e.employeename'; 
  }    
  $query_prm = array();
  require('inc/doquery.php');
  $employeewithoutsecA = $query_result;$num_employeeswithoutsec = $num_results; unset($query,$query_result,$num_results);    

  #DEPARTMENT AND SECTION
  #5: managers with dpt and section
  $query = 'select e.* from employee e, employeedepartment ed, employeesection es where  e.ismanager = 1 and e.employeedepartmentid>0 and e.employeesectionid > 0 and e.employeedepartmentid = ed.employeedepartmentid and e.employeesectionid = es.employeesectionid and e.deleted = 0';
  $query .= ' order by ed.employeedepartmentname,es.employeesectionname';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= ',e.referencenumber';   
  }
  else
  {
    $query .= ',e.employeename'; 
  }    
  $query_prm = array();
  require('inc/doquery.php');
  $managerdptsecA = $query_result;$num_managersdptsec = $num_results;unset($query,$query_result,$num_results);  

  #9: employees with department/section
  $query = 'select e.* from employee e, employeedepartment ed, employeesection es where e.ismanager = 0 and e.employeedepartmentid>0 and e.employeesectionid > 0 and e.employeedepartmentid = ed.employeedepartmentid and e.employeesectionid = es.employeesectionid and e.deleted = 0';
  $query .= '  order by ed.employeedepartmentname';
  if($ds_isemployeereferencesort == 1)
  {
    $query .= ',e.referencenumber';   
  }
  else
  {
    $query .= ',e.employeename'; 
  }    
  $query_prm = array();
  require('inc/doquery.php');
  $employeedptsecA = $query_result;$num_employeesdptsec = $num_results; unset($query,$query_result,$num_results);         
   
  #FILL THE ARRAY TO BE DISPLAYED
  #manager/employee without department/section
  #then manager/employee with department without section by department
  #then manager/employee with department/section by department/section
  $employeedisplayedA = array();
	$employeedisplayedAinitialized = 0;
  #managers without department/section
  if ( $num_managerswithoutdpt > 0 ) 
  { 
    if ($employeedisplayedAinitialized)
    {  
      $employeedisplayedA = array_merge($employeedisplayedA,$managerwithoutdptA); 
    }
    else 
    {   
      $employeedisplayedA = $managerwithoutdptA; 
      $employeedisplayedAinitialized = 1;
    }
  }
  #employees without department/section
  if ( $num_employeeswithoutdpt > 0 ) 
  { 
    if ( $employeedisplayedAinitialized ) 
    {   
      $employeedisplayedA = array_merge($employeedisplayedA,$employeewithoutdptA);     
    }
    else 
    {
      $employeedisplayedA = $employeewithoutdptA; 
      $employeedisplayedAinitialized = 1;
    }
  }
  $temp = count($employeedisplayedA);

  #managers with department 
  for($md=-1;$md<$num_managerswithoutsec;$md++)
  { 
    if ($md == -1) { $md = 0; } 

    $isnextmanagersameteam = 0;
    if ($num_managerswithoutsec > 0)
    {
      $managerdepartmentid = $managerwithoutsecA[$md]['employeedepartmentid'];
      $isnextmanagersameteam = 0;
      array_push($employeedisplayedA, $managerwithoutsecA[$md]);            
      #verify if next manager is also in the same department       
      if ((($md+1) < $num_managerswithoutsec) && ($managerwithoutsecA[$md+1]['employeedepartmentid'] == $managerdepartmentid)) { $isnextmanagersameteam = 1;}
    }
    #employees with department only for the last manager of the team
    if($isnextmanagersameteam == 0)
    {
      for($ed=-1;$ed<$num_employeeswithoutsec;$ed++)
      { 
        if ($ed == -1) { $ed = 0; }         
        if ($num_employeeswithoutsec > 0)
        {        
          $employeedepartmentid = $employeewithoutsecA[$ed]['employeedepartmentid'];  
          if ((($num_managerswithoutsec > 0) && ($managerdepartmentid == $employeedepartmentid)) ||
              ($num_managerswithoutsec == 0))
          {
            array_push($employeedisplayedA, $employeewithoutsecA[$ed]);               
          }
        }
      }
        
      #managers with department and section only for the last super user of the team
      for($m=-1;$m<$num_managersdptsec;$m++)
      {        
        if ($m == -1) { $m = 0; }  
    
        $isnextmanagersameteam_ds = 0;                  
        if ($num_managersdptsec > 0)
        {
          $managerdepartmentid_ds = $managerdptsecA[$m]['employeedepartmentid'];
          $managersectionid_ds = $managerdptsecA[$m]['employeesectionid'];               
          array_push($employeedisplayedA, $managerdptsecA[$m]);                       
          #verify if next manager is also in the same department/section                    
          if ((($m+1) < $num_managersdptsec) && ($managerdptsecA[$m+1]['employeedepartmentid'] == $managerdepartmentid_ds) && ($managerdptsecA[$m+1]['employeesectionid'] == $managersectionid_ds)) 
          { 
            $isnextmanagersameteam_ds = 1;                      
          }
        }

        #employees with department and section only for the last manager of the team
        if($isnextmanagersameteam_ds == 0)
        {
          for($e=-1;$e<$num_employeesdptsec;$e++)
          {           
            if ($e == -1) { $e = 0; }  
            if ($num_employeesdptsec > 0)
            {        
              $employeedepartmentid_ds = $employeedptsecA[$e]['employeedepartmentid'];  
              $employeesectionid_ds = $employeedptsecA[$e]['employeesectionid'];  
              if ((($num_managersdptsec > 0) && ($managerdepartmentid_ds == $employeedepartmentid_ds) && ($managersectionid_ds == $employeesectionid_ds)) ||
                  ($num_managersdptsec == 0))
              {
                array_push($employeedisplayedA, $employeedptsecA[$e]);                     
              }
            }
          }
        }
      }
    }
  }
  }
  $num_employees = count($employeedisplayedA);

  for ($kladd_i=0;$kladd_i<$num_employees;$kladd_i++)
  {
  $employeeid_temp = (int) ($employeedisplayedA[$kladd_i]['employeeid']);
  $employeename_temp = '';
  $referencenumber = $employee_referencenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['referencenumber'];
  $employeesortedbyteam_badgenumberA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['badgenumber'];
  if(($ds_isemployeereferencesort == 1) && (isset($referencenumber)) && $referencenumber != ''){$employeename_temp = $referencenumber . '_';}
  $employeesortedbyteamA[$employeeid_temp] = $employeename_temp . $employeedisplayedA[$kladd_i]['employeename']; 
  if ($employeedisplayedA[$kladd_i]['deleted'] == 1) { $employeesortedbyteamA[$employeeid_temp] .= ' [supprimé]'; } # lang
  $employeesortedbyteam_iscashierA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['iscashier'];
  $employeesortedbyteam_issalesA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['issales'];
  $employeesortedbyteam_jobidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['jobid'];
  $employeesortedbyteam_contractidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['contractid'];
  $employeesortedbyteam_categoryidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeecategoryid'];
  $employeesortedbyteam_deletedA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['deleted'];
  $employeesortedbyteam_scheduleidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['scheduleid'];
  $employeesortedbyteam_ismanagerA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['ismanager'];
  $employeesortedbyteam_interimmanageridA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['interimmanagerid'];
  $employeesortedbyteam_employeedepartmentidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeedepartmentid'];
  $employeesortedbyteam_employeesectionidA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeesectionid'];  
  $employeesortedbyteam_employeeemailA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['employeeemail'];  
  $employeesortedbyteam_hiringdateA[$employeeid_temp] = $employeedisplayedA[$kladd_i]['hiringdate'];  
}
unset($employeeid_temp);
?>