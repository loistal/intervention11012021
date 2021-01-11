<?php

# TODO IMPORTANT replace into => on duplicate key update

require('preload/employee.php');
require('preload/contract.php');
require('preload/job.php');

$employeeid = $_POST['employeeid'] + 0;
if ($employeeid == 0) { $employeeid = $_GET['employeeid'] + 0;}
$employeename = $employeesortedA[$employeeid];

$STEP_FORM_EMPLOYEE = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED = 60;

#title
switch ($currentstep)
{
  case $STEP_FORM:
    echo '<h2>' . d_trad('careerpath').': '.$employeeA[$employeeid] . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addcareerpath') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifycareerpath').': '.$employeeA[$employeeid] . '</h2>';
    $employeecareerpathid = $_GET['employeecareerpathid']+0;   
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . d_trad('careerpath').': '.$employeeA[$employeeid] . '</h2>';
    $employeecareerpathid = NULL; #to be inserted      
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('careerpath').': '.$employeeA[$employeeid] . '</h2>';
    $employeecareerpathid = $_POST['employeecareerpathid']+0;       
    break;       
}

$numrows = 0;
switch($currentstep)
{
  # Form to choose wich employee
  case $STEP_FORM_EMPLOYEE:
    $title = d_trad('careerpath');
    require('hr/chooseemployee.php');
    break;

  # save
  case $STEP_FORM_VALIDATE_MOD:
  case $STEP_FORM_VALIDATE_ADD:
    $numrows = $_POST['numrows']+0;
    $datename = 'startdate'; $dp_allowempty=1; require('inc/datepickerresult.php');    
    $datename = 'stopdate'; $dp_allowempty=1; require('inc/datepickerresult.php');  
    $employer = $_POST['employer'];  
    $jobid = $_POST['jobid'] +0;  
    $contractid = $_POST['contractid'] +0;  
    $job = $_POST['job'];  
    $contract = $_POST['contract'];        
    $comment = $_POST['comment'];  
    $deleted = $_POST['deleted'] + 0;   
    
    $query = 'REPLACE INTO employeecareerpath (employeecareerpathid,employeeid,startdate,stopdate,employer,jobid,contractid,job,contract,comment,deleted) values (?,?,?,?,?,?,?,?,?,?,?)';
    $query_prm = array($employeecareerpathid,$employeeid,$startdate,$stopdate,$employer,$jobid,$contractid,$job,$contract,$comment,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      echo '<p>' . d_trad('careerpathmodified',$employeeA[$employeeid]) . '</p><br>';
    }  
    break;
}
  
if ( $currentstep > $STEP_FORM_EMPLOYEE )
{
  # pre-filled form
  $query = 'select * from employeecareerpath where ';
  if ( $currentstep == $STEP_FORM_MODIFY )
  {
    $employeecareerpathid = $_GET['employeecareerpathid'];
    $employeeid = $_GET['employeeid'];
    $query .= 'employeecareerpathid=?';
    $query_prm = array($employeecareerpathid);
  }
  else
  {
    $query .= 'employeeid=?';
    $query_prm = array($employeeid);    
  }
  if ($ds_showdeleteditems != 1)
  {
    $query .= ' and deleted=0';
  }
  $query .= ' order by startdate';

  require('inc/doquery.php');
  $row = NULL;
  $numrows = $num_results;
  if ($numrows > 0 )
  {
    $row = $query_result; 
  }
   ?>
  <form method="post" action="hr.php">
  <?php if ( $numrows == 0 && $currentstep != $STEP_FORM_ADD)
  { 
    $currentstep = $STEP_FORM_ADD;
  }
  
  if( $numrows > 0 || ($currentstep == $STEP_FORM_ADD))
  {
    if (( $currentstep == $STEP_FORM_MODIFY) || ($currentstep == $STEP_FORM_ADD))
    {
      echo '<table>';
    }
    else
    {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('startdate'); ?></th>
        <th><?php echo d_trad('stopdate'); ?></th>
        <th><?php echo d_trad('employer'); ?></th>
        <th><?php echo d_trad('job'); ?></th>
        <th><?php echo d_trad('contract'); ?></th>
        <th><?php echo d_trad('comment'); ?></th>
        <?php 
        if(( $ds_showdeleteditems  || $currentstep == $STEP_FORM_MODIFY))
        {
          echo '<th>' . d_trad('deleted') . '</th>';
        } ?>
      </thead><?php 
    }
  }
  
  if ( $currentstep ==  $STEP_FORM  || $currentstep >= $STEP_FORM_VALIDATE_ADD)
  {
    for ($r=0;$r<$numrows;$r++)
    {
      $employeecareerpathid = $row[$r]['employeecareerpathid'];
      $href = 'hr.php?hrmenu=careerpath&step=' . $STEP_FORM_MODIFY . '&employeeid=' . $employeeid . '&employeecareerpathid=' . $employeecareerpathid;
      echo d_tr();
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['startdate']) . '</a></td>';
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['stopdate']) . '</a></td>';
      $employerdisplay = $row[$r]['employer'];
      if ( strlen($employerdisplay) >= $MAX_LENGTH_DISPLAYED ) { $employerdisplay = substr($employerdisplay,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($employerdisplay). '</a></td>';
      
      $jobid = $row[$r]['jobid'];
      if ($jobid > 0) 
      {
        $jobdisplay = $jobA[$jobid] . '&nbsp;';
      }
      if (isset($row[$r]['job']))
      {
        $jobdisplay .= $row[$r]['job'];
      }
      if ( strlen($jobdisplay) >= $MAX_LENGTH_DISPLAYED ) { $jobdisplay = substr($jobdisplay,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($jobdisplay). '</a></td>';      
      
      $contractid = $row[$r]['contractid'];
      if ($contractid > 0) 
      {
        $contractdisplay = $contractA[$contractid] . '&nbsp;';
      }
      if (isset($row[$r]['contract']))
      {
        $contractdisplay .= $row[$r]['contract'];
      }
      if ( strlen($contractdisplay) >= $MAX_LENGTH_DISPLAYED ) { $contractdisplay = substr($contractdisplay,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($contractdisplay). '</a></td>';

      $commentdisplay = $row[$r]['comment'];
      if ( strlen($commentdisplay) >= $MAX_LENGTH_DISPLAYED ) { $commentdisplay = substr($commentdisplay,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($commentdisplay). '</a></td>';
      
      if ( $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
  }
  else if ($currentstep == $STEP_FORM_ADD)
  {
		#echo '<tr><td>' . d_trad('name:') . '</td><td>' . $employeename . '</td></tr>';
		echo '<input type=hidden name="employeeid" value="' . $employeeid. '">';  		
    echo '<tr><td>' . d_trad('startdate:') . '</td><td colspan=4>';
    $datename = 'startdate'; $dp_datepicker_min=1920; require('inc/datepicker.php');
    echo '</td></tr>';    
    echo '<tr><td>' . d_trad('stopdate:') . '</td><td colspan=4>';
    $datename = 'stopdate'; $dp_datepicker_min=1920; require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td>' . d_trad('employer:') . '</td>';
    echo '<td colspan=4><input type=text name="employer"></td></tr>';  
		echo '<tr><td>' . d_trad('job:') . '</td>';			
    $dp_itemname = 'job'; $dp_description = '';require('inc/selectitem.php'); 
    echo '<td>' . d_trad('or') . '</td>';
    echo '<td>' . d_trad('other:') . '</td>';
    echo '<td><input type=text name="job"></td></tr>';    
		echo '<tr><td>' . d_trad('contract:') . '</td>';			
    $dp_itemname = 'contract'; $dp_description = '';
    require('inc/selectitem.php'); 
    echo '<td>' . d_trad('or') . '</td>';
    echo '<td>' . d_trad('other:') . '</td>';
    echo '<td><input type=text name="contract"></td></tr>';
    echo '<tr><td style="vertical-align:top;">' . d_trad('comment:') . '</td>';
    echo '<td colspan=4><textarea name="comment" rows=2 cols=80></textarea></td></tr>';
  }
  else if ( $currentstep == $STEP_FORM_MODIFY)
  {
    $careerpath = $row[0];
		#echo '<tr><td>' . d_trad('name:') . '</td><td>' . $employeename . '</td></tr>';
		echo '<input type=hidden name="employeeid" value="' . $employeeid. '">'; 		
    echo '<tr><td>' . d_trad('startdate:') . '</td><td colspan=4>';
    $datename = 'startdate'; $dp_datepicker_min=1920; 
    if (isset($careerpath['startdate'])) {$selecteddate=$careerpath['startdate'];} else {$dp_setempty=1;} require('inc/datepicker.php');
    echo '</td></tr>';    
    echo '<tr><td>' . d_trad('stopdate:') . '</td><td colspan=4>';
    $datename = 'stopdate'; $dp_datepicker_min=1920; 
    if (isset($careerpath['stopdate'])) {$selecteddate=$careerpath['startdate'];} else {$dp_setempty=1;}  require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr>';
    echo '<td>' . d_trad('employer:') . '</td>';
    echo '<td colspan=4><input type=text name="employer" value="' . d_input($careerpath['employer']) . '"></td></tr>';  
		echo '<tr><td>' . d_trad('job:') . '</td>';		
    $dp_itemname = 'job'; $dp_description = ''; $dp_selectedid = $careerpath['jobid'];
    require('inc/selectitem.php'); 
    echo '<td>' . d_trad('or') . '</td>';
    echo '<td>' . d_trad('other:') . '</td>';
    echo '<td><input type=text name="job" value="' . d_input($careerpath['job']) . '"></td></tr>';    
		echo '<tr><td>' . d_trad('contract:') . '</td>';
    $dp_itemname = 'contract'; $dp_description = ''; $dp_selectedid = $careerpath['contractid'];
    require('inc/selectitem.php'); 
    echo '<td>' . d_trad('or') . '</td>';
    echo '<td>' . d_trad('other:') . '</td>';
    echo '<td><input type=text name="contract" value="' . d_input($careerpath['contract']) . '"></td></tr>';
    echo '<tr><td style="vertical-align:top;">' . d_trad('comment:') . '</td>';
    echo '<td colspan=4><textarea name="comment" rows=2 cols=80>' . d_input($careerpath['comment']) . '</textarea></td></tr>';
    echo '<tr><td>' . d_trad('deleted:') . '</td><td colspan=4><input type=checkbox name="deleted" value=1 ';
    if ($careerpath['deleted']) { echo ' checked '; }
    echo '></td></tr>';
    echo '<input type=hidden name="employeecareerpathid" value="' . $careerpath['employeecareerpathid'] . '">';
  }
} ?>
</table>
<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="employeeid" value="<?php echo $employeeid; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
if (1==1)
{
  if ($currentstep == $STEP_FORM || $currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_ADD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_MODIFY)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  } 
}
?>
</table>
</form>