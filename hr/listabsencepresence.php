<?php
require('preload/planningteamvalue.php');

$STEP_FORM_ABSPRES = 0;
$STEP_FORM = 1;

$STATE_SAVED = 9;
$STATE_SUBMITED = 0;
$STATE_ACCEPTED = 1;
$STATE_REFUSED = 2;
$NUM_STATES = 3;
$ALL_STATES = -1;

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';

$STEP_FORM_DIRECTACCESS = 5;

$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];

#title
$title = d_trad('abspresencerequests');
echo '<h2>' . $title . '</h2>';

if ($currentstep == $STEP_FORM_ABSPRES)
{   
  ?>    
  <form method="post" action="hr.php"><table>
  <?php require('hr/chooseemployeewithteamsform.php');
  echo '<tr><td>' .  d_trad('state:') . '</td><td colspan=2>';
  echo '<select name=state>';
  echo '<option value=' . $ALL_STATES .'>' . d_trad('all') . '</option>';  
  for($st=0;$st<$NUM_STATES;$st++)
  {
    echo '<option value=' . $st .'>' . d_trad('absencestate' .$st) . '</option>';
  }
  echo '</select></td></tr>';
  echo '<tr><td>' . d_trad('date:') . '</td><td colspan=2>';
  $datename = 'planningstart'; 
  #date by default: beginning and end of civil year
  $ds_curdate = $_SESSION['ds_curdate'];
  $currentyear = mb_substr($ds_curdate,0,4);
  $selecteddate = $currentyear . '-01-01';    
  require('inc/datepicker.php');
  echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
  $datename = 'planningstop'; 
  $selecteddate = $currentyear . '-12-31';      
  require('inc/datepicker.php');
  echo '</td></tr>';?>
  
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value=<?php echo $STEP_FORM;?>><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" value="<?php echo d_trad('list');?>"></td></tr>
  </table></form><?php
}
else if($currentstep == $STEP_FORM)
{
  $state = $_POST['state']+0;
  $employeeid_save = $employeeid = $_POST['employeeid'];
  $datename = 'planningstart';
  require('inc/datepickerresult.php');
  
  $datename = 'planningstop';
  require('inc/datepickerresult.php');
  
  $query = 'select * from planningteam p';
  if ($employeeid == $ALL)
  {
    $query .= ' where p.periodic=0';  
    $query_prm = array();
  }
  else if (($employeeid == $MYTEAM) || ($employeeid == $TEAMIMANAGE) ) # employees from the same team
  {
    $query .= ',employee e where e.employeeid = p.employeeid and p.periodic=0 and e.employeedepartmentid = ? and e.employeesectionid = ?';
    $query_prm = array($myemployeedepartmentid,$myemployeesectionid);
    #so I am not considered as a manager in this team but as an employee
    if ($employeeid == $MYTEAM) {$ismanager = 0;}
  }
  elseif ($employeeid < 0) 
  {  
    # employees from another team
    #delete the "-" before employeeid_save and split departmentid and sectionid
    $posunderscore = mb_stripos($employeeid,'_');
    $len = mb_strlen($employeeid);
    $employeedepartmentid = mb_substr($employeeid,1,$posunderscore-1);
    $employeesectionid = mb_substr($employeeid,$posunderscore + 1,$len);
    $query .= ',employee e where e.employeeid = p.employeeid and p.periodic=0 and e.employeedepartmentid = ? and e.employeesectionid = ?';
    $query_prm = array($employeedepartmentid,$employeesectionid);
  } 
  else
  {
    $query .= ' where p.employeeid=? and p.periodic=0';
    $query_prm = array($employeeid);
  }  
  if ($state > $ALL_STATES)
  {
    $query .= ' and p.state=?';
    array_push($query_prm,$state);
  }
  $query .= ' and ((p.planningdate >= ? and p.planningdate <= ?) or (planningstart>=? and planningstop<=?) or (planningstart<=? and planningstop>=?) or (planningstart<=? and planningstop>=?))';
  array_push($query_prm,$planningstart,$planningstop,$planningstart,$planningstop,$planningstart,$planningstart,$planningstop,$planningstop);
  if ($ds_showdeleteditems == 0)
  {
    $query .= ' and p.deleted=0';
  }
  $query .= ' order by p.planningdate, p.planningstart,p.employeeid,p.planningteamcomplexid'; 
  require('inc/doquery.php');
  if ($num_results ==0)
  {
    echo '<p>' . d_trad('noresult') .'</p>';
  }
  else
  {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('name'); ?></th>   
        <th><?php echo d_trad('date'); ?></th>               
        <th><?php echo d_trad('state'); ?></th> 
        <?php if ($ds_showdeleteditems)
        {
          echo '<th>' . d_trad('deleted')  . '</th>';
        } ?>       
      </thead><?php 
      $planningteamcomplexid_prev = 0;
      $planningcomplexstart = 0;
      for($p=0;$p<$num_results;$p++)
      {
        $row = $query_result[$p];
        $empid = $row['employeeid'];
        $date = $datestart = $datestop = $row['planningdate'];
        $datedisplayed = datefix2($row['planningdate']); 
        $state = $row['state'];
        $pteamid = $row['planningteamid'];
        $planningteamcomplexid = $row['planningteamcomplexid']+0;
        $planningteamcomplexid_next = 0;
        if(($p +1 ) < $num_results) { $planningteamcomplexid_next = $query_result[$p+1]['planningteamcomplexid']+0;}
        $deleted = $row['deleted'];
        
        if ($date == NULL)  
        { 
          $date = $row['planningstart'];      
          $datestop = $row['planningstop'];   
          $datedisplayed = d_trad('fromto',array(datefix2($date),datefix2($datestop)));           
        }
        #if manager, request is not for himself
        $ismanagerbutnothimself = 0;
        if ($ds_ishrsuperuser || ($ismanager && ($empid != $ds_myemployeeid))) { $ismanagerbutnothimself = 1;}
        $href = '';
        $ishref = 0;
        $isdisplayed = 1;
        if ($ds_isaddabspresenceaccess) 
        { 
          $ishref = 1;
          #check if it is a complex or simple absence request
          if ($planningteamcomplexid > 0)
          {        
            #only one line for planningteam who have the same planningteamcomplexid (last record)
            #we take date from 1rst and last record
            if ($planningteamcomplexid == $pteamid)
            {
              $isdisplayed = 0;            
              $planningcomplexstart = $date;              
            }
            elseif (($planningteamcomplexid_prev == $planningteamcomplexid) && ($planningteamcomplexid != $planningteamcomplexid_next))
            {
              $isdisplayed = 1;
              $datedisplayed = d_trad('fromto',array(datefix2($planningcomplexstart),datefix2($date)));   
              $href= '<a href="hr.php?hrmenu=addcomplexabsencepresence&step=' . $STEP_FORM_DIRECTACCESS . '&planningteamcomplexid=' .$planningteamcomplexid . '&employeeid=' . $empid .'&planningstart=' . $planningcomplexstart .'&planningstop=' . $date . '&deleted=' . $deleted .'" target=_blank>';            
            }
            else
            {
              $isdisplayed = 0;
            }
          }
          else
          {        
            $isdisplayed = 1; 
            $href= '<a href="hr.php?hrmenu=addabsencepresence&modid=' . $pteamid .'&date=' . $date . '" target="_blank">';          
          }
        }
        $planningteamcomplexid_prev = $planningteamcomplexid;

        if ($isdisplayed)
        {
          echo d_tr();     
          echo '<td>';
          if ($ishref) 
          { 
            echo $href;
            echo d_output($employeesortedA[$empid]);           
            echo '</a></td><td>' . $href . $datedisplayed . '</a></td>';
            echo '<td>' . $href . d_trad('absencestate'.$state) . '</a></td>';
            if ($ds_showdeleteditems)
            {
              echo '<td align=center>' . $href;
              if ($deleted == 1) { echo '&radic;'; }
              echo '</a></td>';
            }
          }
          else
          {
            echo d_output($employeesortedA[$empid]);
            echo '</td><td>' . $datedisplayed . '</td>';
            echo '<td>' . d_trad('absencestate'.$state) . '</td>';
            if ($ds_showdeleteditems)
            {
              echo '<td align=center>';
              if ($row['deleted'] == 1) { echo '&radic;'; }
              echo '</td>';
            }        
          }
        }
        echo '</tr>';
      }
  }
}
?>