<?php

# TODO IMPORTANT replace into => on duplicate key update

require('preload/employee.php');

$employeeid = $_POST['employeeid'] + 0;
if ($employeeid == 0) { $employeeid = $_GET['employeeid'] + 0;}
$employeename = $employeesortedA[$employeeid];

$STEP_FORM_EMPLOYEE = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED_COMMENT = 60;

#title
switch ($currentstep)
{
  case $STEP_FORM:
  case $STEP_FORM_VALIDATE_ADD: 
  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('medicalcheckup') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addmedicalcheckup') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifymedicalcheckup') . '</h2>';
    break;        
}

$numrows = 0;
switch($currentstep)
{
  # Form to choose wich employee
  case $STEP_FORM_EMPLOYEE:
    $title = d_trad('medicalcheckup');
    require('hr/chooseemployee.php');
    break;

  # save
  case $STEP_FORM_VALIDATE_MOD:
    $numrows = $_POST['numrows'];
    for ($r=0;$r<$numrows;$r++)
    {
      $employeemedicalcheckupid = $_POST['employeemedicalcheckupid'.$r];    
      $date = $_POST['date'.$r];    
      $comment = $_POST['comment'.$r];  
      $deleted = $_POST['deleted'.$r] + 0;   
      
      $query = 'REPLACE INTO employeemedicalcheckup (employeemedicalcheckupid,employeeid,date,comment,deleted) values (?,?,?,?,?)';
      $query_prm = array($employeemedicalcheckupid,$employeeid,$date,$comment,$deleted);
      require ('inc/doquery.php');
      if ( $num_results > 0 )
      {
        echo '<p>' . d_trad('medicalcheckupmodified',$employeename) . '</p><br>';
      }  
    }
    break;
    
  #insert
  case $STEP_FORM_VALIDATE_ADD:
    $numrows = $_POST['numrows'];
    $date = $_POST['date'.$numrows];    
    $comment = d_input($_POST['comment'.$numrows]);  
    $deleted = $_POST['deleted'.$numrows] + 0;   
    $numrows ++;
    
    $query = 'INSERT INTO employeemedicalcheckup (employeeid,date,comment,deleted) values (?,?,?,?)';
    $query_prm = array($employeeid,$date,$comment,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      echo '<p>' . d_trad('medicalcheckupadded',$employeename) . '</p><br>';
    }  
    break;
}
  
if ( $currentstep > $STEP_FORM_EMPLOYEE )
{
  # pre-filled form
  $query = 'select * from employeemedicalcheckup where ';
  if ( $currentstep == $STEP_FORM_MODIFY )
  {
    $employeemedicalcheckupid = $_GET['employeemedicalcheckupid'];
    $employeeid = $_GET['employeeid'];
    $query .= 'employeemedicalcheckupid=?';
    $query_prm = array($employeemedicalcheckupid);
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
    echo '<p>' . d_trad('noresult') . '</p>';
  }
  else
  {
    if ( $currentstep == $STEP_FORM_MODIFY )
    {
      echo '<table>';
    }
    else
    {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('date'); ?></th>
        <th><?php echo d_trad('comment'); ?></th>
        <?php 
        if(( $ds_showdeleteditems  || $currentstep == $STEP_FORM_MODIFY) && $currentstep != $STEP_FORM_ADD)
        {
          echo '<th>' . d_trad('deleted') . '</th>';
        } ?>
      </thead><?php 
    }
  }
  
  if ( $currentstep ==  $STEP_FORM || $currentstep == $STEP_FORM_ADD || $currentstep >= $STEP_FORM_VALIDATE_ADD)
  {
    for ($r=0;$r<$numrows;$r++)
    {
      $employeemedicalcheckupid = $row[$r]['employeemedicalcheckupid'];
      $href = 'hr.php?hrmenu=medicalcheckup&step=' . $STEP_FORM_MODIFY . '&employeeid=' . $employeeid . '&employeemedicalcheckupid=' . $employeemedicalcheckupid;
      echo d_tr();
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['date']) . '</a></td>';
      $commentdisplay = $row[$r]['comment'];
      if ( strlen($commentdisplay) >= $MAX_LENGTH_DISPLAYED_COMMENT ) { $commentdisplay = substr($commentdisplay,0,$MAX_LENGTH_DISPLAYED_COMMENT) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($commentdisplay). '</a></td>';
      
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
    
    if ($currentstep == $STEP_FORM_ADD)
    {
      echo d_tr();
      echo '<td>';
      $datename = 'date' . $numrows; $dp_datepicker_min=1920; require('inc/datepicker.php');
      echo '</td>';
      echo '<td><textarea text name="comment' . $numrows . '" cols=' . $MAX_LENGTH_DISPLAYED_COMMENT . '></textarea></td>';
      echo '</tr>';   
    }
  }
  else if ( $currentstep == $STEP_FORM_MODIFY)
  {
    for ($r=0;$r<$numrows;$r++)
    {
      echo '<tr><td>' . d_trad('date:') . '</td><td>';
      $datename = 'date' . $r; $dp_datepicker_min=1920; $selecteddate=$row[$r]['date']; require('inc/datepicker.php');
      echo '</td></tr>';
      echo '<tr><td style="vertical-align:top;">' . d_trad('comment:') . '</td>';
      echo '<td><textarea name="comment' . $r . '" rows=30 cols=80>' . d_input($row[$r]['comment']) . '</textarea></td></tr>';
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted' . $r . '" value=1 ';
      if ($row[$r]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="employeemedicalcheckupid' .$r . '" value="' . $row[$r]['employeemedicalcheckupid'] . '">';
    }
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