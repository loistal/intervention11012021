<?php

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
    echo '<h2>' . d_trad('annualinterview') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addannualinterview') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifyannualinterview') . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . d_trad('annualinterview') . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('annualinterview:') . '</h2>';
    break;       
}

$numrows = 0;
switch($currentstep)
{
  # Form to choose wich employee
  case $STEP_FORM_EMPLOYEE:
    $title = d_trad('annualinterview');
    require('hr/chooseemployee.php');
    break;

  # save
  case $STEP_FORM_VALIDATE_MOD:
    $numrows = $_POST['numrows'];
    for ($r=0;$r<$numrows;$r++)
    {
      $isdisabled = $_POST['isdisabled'.$r] + 0;    
      $employeeannualinterviewid = $_POST['employeeannualinterviewid'.$r];    
      $validatedbyemployee = $_POST['validatedbyemployee'.$r] + 0; 
      
      if ( $isdisabled )
      {
        $query = 'update employeeannualinterview set validatedbyemployee=? where employeeannualinterviewid = ?';
        $query_prm = array($validatedbyemployee,$employeeannualinterviewid);
      }
      else
      {
        $date = $_POST['date'.$r];    
        $comment = $_POST['comment'.$r] . '';  
        $submittedtoemployee = $_POST['submittedtoemployee'.$r] + 0;
        $deleted = $_POST['deleted'.$r] + 0;         

        $query = 'REPLACE INTO employeeannualinterview (employeeannualinterviewid,employeeid,date,comment,submittedtoemployee,validatedbyemployee,deleted) values (?,?,?,?,?,?,?)';
        $query_prm = array($employeeannualinterviewid,$employeeid,$date,$comment,$submittedtoemployee,$validatedbyemployee,$deleted);
      }
      require ('inc/doquery.php');
      if ( $num_results > 0 )
      {
        echo '<p>' . d_trad('annualinterviewmodified',$employeename) . '</p><br>';
      }  
    }
    break;
    
  #insert
  case $STEP_FORM_VALIDATE_ADD:
		$datename = 'date0'; require('inc/datepickerresult.php');
    $comment = $_POST['comment0'] . '';   
    $submittedtoemployee = $_POST['submittedtoemployee0'] + 0;
    $validatedbyemployee = $_POST['validatedbyemployee0'] + 0;   
    
    $query = 'INSERT INTO employeeannualinterview (employeeid,date,comment,submittedtoemployee,validatedbyemployee) values (?,?,?,?,?)';
    $query_prm = array($employeeid,$date0,$comment,$submittedtoemployee,$validatedbyemployee);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      echo '<p>' . d_trad('annualinterviewadded',$employeename) . '</p><br>';
    }  
    break;
}
  
if ( $currentstep > $STEP_FORM_EMPLOYEE )
{
  # pre-filled form
  $query = 'select * from employeeannualinterview where ';
  if ( $currentstep == $STEP_FORM_MODIFY )
  {
    $employeeannualinterviewid = $_GET['employeeannualinterviewid'];
    $employeeid = $_GET['employeeid'];
    $query .= 'employeeannualinterviewid=?';
    $query_prm = array($employeeannualinterviewid);
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
	$query .= ' order by date desc';

  require('inc/doquery.php');
  $row = NULL;
  $numrows = $num_results;
  if ($numrows > 0 )
  {
    $row = $query_result; 
  }
   ?>
  <form method="post" action="hr.php">
  <?php 
  if ( $numrows == 0 && ($currentstep != $STEP_FORM_ADD))
  { 
    echo '<p>' . d_trad('noresult') . '</p>';
  }
  else
  {    
    if ( $currentstep == $STEP_FORM_MODIFY || $currentstep == $STEP_FORM_ADD)
    {
      echo '<table>';
    }
    else
    {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('date'); ?></th>
        <th><?php echo d_trad('comment'); ?></th>        
        <th><?php echo d_trad('submittedtoemployee'); ?></th>
        <th><?php echo d_trad('validatedbyemployee'); ?></th>
        <?php
        if(( $ds_showdeleteditems  || $currentstep == $STEP_FORM_MODIFY) && $currentstep != $STEP_FORM_ADD)
        {
          echo '<th>' . d_trad('deleted') . '</th>';
        } ?>
      </thead><?php 
    }
  }
  
  if ( $currentstep ==  $STEP_FORM || $currentstep >= $STEP_FORM_VALIDATE_ADD)
  {
    for ($r=0;$r<$numrows;$r++)
    {
      $employeeannualinterviewid = $row[$r]['employeeannualinterviewid'];
      $href = 'hr.php?hrmenu=annualinterview&step=' . $STEP_FORM_MODIFY . '&employeeid=' . $employeeid . '&employeeannualinterviewid=' . $employeeannualinterviewid;
      echo d_tr();
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['date']) . '</a></td>';
      $commentdisplay = d_output($row[$r]['comment']);    
      if ( strlen($commentdisplay) >= $MAX_LENGTH_DISPLAYED_COMMENT ) { $commentdisplay = substr($commentdisplay,0,$MAX_LENGTH_DISPLAYED_COMMENT) . '...'; }
      echo '<td><a href="' . $href . '">' . $commentdisplay. '</a></td>';      
      echo '<td align=center><a href="' . $href . '">';
      if ($row[$r]['submittedtoemployee'] == 1) { echo '&radic;'; }
      echo '</a></td>';
      echo '<td align=center><a href="' . $href . '">';
      if ($row[$r]['validatedbyemployee'] == 1) { echo '&radic;'; }
      echo '</a></td>';      
      
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
  }
  else if ( $currentstep == $STEP_FORM_MODIFY || $currentstep == $STEP_FORM_ADD)
  {

		if ( $currentstep == $STEP_FORM_MODIFY)
		{
			$date = $row[0]['date'];
			$comment = d_output($row[0]['comment']);
			$submittedtoemployee = $row[0]['submittedtoemployee'];
			$validatedbyemployee = $row[0]['validatedbyemployee'];
			$employeeannualinterviewid = $row[0]['employeeannualinterviewid'];
			$deleted = $row[0]['deleted'];

			$disabled = ' disabled="disabled"';
			$isdisabled = 1;
			if ( $ds_ishrsuperuser ||  $ismanager)
			{
				$disabled = '';
				$isdisabled = 0;
			}  
		}
		else
		{
			$date = NULL; $comment = $disabled = ''; $validatedbyemployee = $employeeannualinterviewid = $deleted = $isdisabled = 0;
		}
		
    echo '<input type=hidden name=isdisabled0 value=' . $isdisabled . '>';

    echo '<tr><td>' . d_trad('date:') . '</td><td>';
    if ($isdisabled)
    {
      echo datefix2($date);    
    }
    else
    {
      $datename = 'date0'; $dp_datepicker_min=1920; $selecteddate=$date; require('inc/datepicker.php');
    }
    echo '</td></tr>';
    echo '<tr><td style="vertical-align:top;">' . d_trad('comment:') . '</td>';
    echo '<td><textarea name="comment0" rows=30 cols=80' . $disabled . '>' . d_input($comment) . '</textarea></td></tr>';   
    
    echo '<tr><td>' . d_trad('submittedtoemployee:') . '</td><td><input type=checkbox name="submittedtoemployee0" value=1 ';
    if ($submittedtoemployee == 1) { echo ' checked '; }
    echo $disabled . '></td></tr>';
    
    echo '<tr><td>' . d_trad('validatedbyemployee:') . '</td><td><input type=checkbox name="validatedbyemployee0" value=1 ';
    if ($validatedbyemployee == 1) { echo ' checked '; }
    echo '></td></tr>'; 
    
    echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted0" value=1 '; 
    if ($deleted == 1) { echo ' checked '; }
    echo $disabled . '></td></tr>';
    
    echo '<input type=hidden name="employeeannualinterviewid0" value="' . $row[0]['employeeannualinterviewid'] . '">';
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
}
if ($currentstep == $STEP_FORM_MODIFY)
{
  echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
} 

?>
</table>
</form>