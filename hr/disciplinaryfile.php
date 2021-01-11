<?php

# TODO IMPORTANT replace into => on duplicate key update

require('preload/employee.php');

$STEP_FORM_EMPLOYEE = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED_COMMENT = 60;

$DISCIPLINARY_FILE_OBSERVATION = 1;
$DISCIPLINARY_FILE_WARNING = 2;
$DISCIPLINARY_FILE_SUSPENSION = 3;
$DISCIPLINARY_FILE_NOTIFICATION = 4;
$DISCIPLINARY_FILE_BLAME = 5;
$DISCIPLINARY_FILE_DOWNGRADING = 6;
$DISCIPLINARY_FILE_TRANSFER = 7;
#licenciement pour faute réelle et sérieuse
$DISCIPLINARY_FILE_DISMISSAL1 = 8;
#licenciement pour faute grave (sans préavis ni indemnité)
$DISCIPLINARY_FILE_DISMISSAL2 = 9;
#licenciement pour faute lourde (ni préavis, ni indemnité, ni congés payés)
$DISCIPLINARY_FILE_DISMISSAL3 = 10;

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';

$employeeid = $_POST['employeeid'];
$employeeid_posted = $employeeid;
if (!isset($employeeid)) { $employeeid = $_GET['employeeid'];}

$isonlyoneemployee = 1;
if ($employeeid == $ALL || $employeeid == $MYTEAM || $employeeid == $TEAMIMANAGE || $employeeid <= 0) { $isonlyoneemployee = 0;}

$level = $_POST['level']+0;

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

#title
switch ($currentstep)
{
  case $STEP_FORM_EMPLOYEE:
    echo '<h2>' . d_trad('disciplinaryfile') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('adddisciplinaryfile') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifydisciplinaryfile') . '</h2>';
    break;   
    
  case $STEP_FORM:
  case $STEP_FORM_VALIDATE_ADD:
  case $STEP_FORM_VALIDATE_MOD:  
    echo '<h2>' . d_trad('disciplinaryfile') . '</h2>';
    break;       
}

$numrows = 0;
switch($currentstep)
{
  # Form to choose wich employee
  case $STEP_FORM_EMPLOYEE:
  
    echo '<form method="post" action="hr.php"><table>';
    $dp_isform = 0;require('hr/chooseemployee.php'); #$dp_isform = 0;require('hr/chooseemployeewithteamsform.php');
    echo '<tr><td>' . d_trad('disciplinaryfilelevel:') . '</td>';
    echo '<td><select name="level">';
    echo '<option value=-1>' . d_trad('all') . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_WARNING . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_WARNING) . '</option>';	
		echo '<option value=' . $DISCIPLINARY_FILE_BLAME . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_BLAME) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_NOTIFICATION . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_NOTIFICATION) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL1 . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL1) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL2 . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL2) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL3 . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL3) . '</option>';
	  echo '<option value=' . $DISCIPLINARY_FILE_SUSPENSION . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_SUSPENSION) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_TRANSFER . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_TRANSFER) . '</option>';		
    echo '<option value=' . $DISCIPLINARY_FILE_OBSERVATION . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_OBSERVATION) . '</option>';
    echo '<option value=' . $DISCIPLINARY_FILE_DOWNGRADING . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DOWNGRADING) . '</option>';
    echo '</select></td></tr>';    
		echo '<tr><td>' . d_trad('date:') . '</td><td colspan=2>';
		$datename = 'startdate'; 
		#date by default: beginning and end of civil year
		$ds_curdate = $_SESSION['ds_curdate'];
		$currentyear = mb_substr($ds_curdate,0,4);
		$selecteddate = $currentyear . '-01-01';    
		require('inc/datepicker.php');
		echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
		$datename = 'stopdate'; 
		$selecteddate = $currentyear . '-12-31';      
		require('inc/datepicker.php');
		echo '</td></tr>';
    break;

  # save
  case $STEP_FORM_VALIDATE_MOD:
    $employeedisciplinaryfileid = $_POST['employeedisciplinaryfileid'];    
    $date = $_POST['date'];    
    $comment = $_POST['comment'];  
    $level = $_POST['level']+0;
    $deleted = $_POST['deleted'] + 0;   
    
    $query = 'REPLACE INTO employeedisciplinaryfile (employeedisciplinaryfileid,employeeid,date,comment,level,deleted) values (?,?,?,?,?,?)';
    $query_prm = array($employeedisciplinaryfileid,$employeeid,$date,$comment,$level,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      echo '<p>' . d_trad('disciplinaryfilemodified',$employeeA[$employeeid]) . '</p><br>';
    }  
    break;
    
  #insert
  case $STEP_FORM_VALIDATE_ADD:
    $date = $_POST['date'];    
    $comment = d_input($_POST['comment']);  
    $level = $_POST['level']+0;
    $deleted = $_POST['deleted'] + 0;     
    
    $query = 'INSERT INTO employeedisciplinaryfile (employeeid,date,comment,level,deleted) values (?,?,?,?,?)';
    $query_prm = array($employeeid,$date,$comment,$level,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      $numrows ++;
      echo '<p>' . d_trad('disciplinaryfileadded',$employeeA[$employeeid]) . '</p><br>';
    }  
    break;
}

if ( $currentstep > $STEP_FORM_EMPLOYEE)
{
  if ( $currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form
    $query_prm = array();
    if ( $currentstep == $STEP_FORM_MODIFY || $currentstep == $STEP_FORM_VALIDATE_MOD)
    {
			if ($currentstep == $STEP_FORM_MODIFY)
			{
				$employeedisciplinaryfileid = $_GET['employeedisciplinaryfileid'];
				$employeeid = $_GET['employeeid'];
			}
			else
			{
				$employeedisciplinaryfileid = $_POST['employeedisciplinaryfileid'];
			}
      $query = 'select * from employeedisciplinaryfile edf where employeedisciplinaryfileid=? and';
      array_push($query_prm,$employeedisciplinaryfileid);
    }
    else
    {
      $query = 'select * from employeedisciplinaryfile edf,employee e where e.employeeid = edf.employeeid  and';
      if (($employeeid == $MYTEAM) || ($employeeid == $TEAMIMANAGE) ) # employees from the same team
      {
        $query .= ' e.employeedepartmentid = ? and e.employeesectionid = ? and';
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
        $query .= ' e.employeedepartmentid = ? and e.employeesectionid = ? and';
        $query_prm = array($employeedepartmentid,$employeesectionid);
      } 
      else if($employeeid != $ALL)
      {
        $query .= ' edf.employeeid=? and';
        $query_prm = array($employeeid);
      }  
      
      if ($level > 0 && $currentstep != $STEP_FORM_VALIDATE_MOD && $currentstep != $STEP_FORM_VALIDATE_ADD)
      {
        $query .= ' level=? and';
        array_push($query_prm,$level);    
      }   

			if (isset($startdate) && $startdate != '') 
			{
				$query .= ' date >= ? and';
				array_push($query_prm,$startdate);
			}
			
			if (isset($stopdate) && $stopdate != '') 
			{
				$query .= ' date <= ? and';
				array_push($query_prm,$stopdate);
			}
    }
    
    if ($ds_showdeleteditems != 1)
    {
      $query .= ' edf.deleted=0';
    }
    else
    {
      #if query endswith 'and' : delete it
      $posand = strripos($query,'and');
      if ( $posand == (strlen($query) - strlen('and')))
      {
        $query = substr($query,0,$posand);
      }
    }

    require('inc/doquery.php');
    $row = NULL;
    $numrows = $num_results;
    if ($numrows > 0 )
    {
      $row = $query_result; 
    }
  }
   ?>
  <form method="post" action="hr.php">
  <?php if ( $numrows == 0 && $currentstep != $STEP_FORM_ADD)
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
        <?php if ($isonlyoneemployee == 0){ echo '<th>' . d_trad('name') . '</th>';} ?>      
        <th><?php echo d_trad('date'); ?></th>
        <th><?php echo d_trad('comment'); ?></th>
        <th><?php echo d_trad('disciplinaryfilelevel'); ?></th>
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
      $employeedisciplinaryfileid = $row[$r]['employeedisciplinaryfileid'];
      $employeeid = $row[$r]['employeeid'];
      $href = 'hr.php?hrmenu=disciplinaryfile&step=' . $STEP_FORM_MODIFY . '&employeeid=' . $employeeid . '&employeedisciplinaryfileid=' . $employeedisciplinaryfileid;
      echo d_tr();
      if ($isonlyoneemployee == 0){ echo '<td><a href="' . $href . '">' . $employeeA[$employeeid] . '</a></td>';}
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['date']) . '</a></td>';
      $commentdisplay = $row[$r]['comment'];
      if ( strlen($commentdisplay) >= $MAX_LENGTH_DISPLAYED_COMMENT ) { $commentdisplay = substr($commentdisplay,0,$MAX_LENGTH_DISPLAYED_COMMENT) . '...'; }
      echo '<td><a href="' . $href . '">' . d_output($commentdisplay). '</a></td>';
      $level = $row[$r]['level'];
      echo '<td><a href="' . $href . '">' . d_trad('disciplinaryfilelevel'.$level). '</a></td>';
      
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';       
    }
  }  
  else if ( $currentstep == $STEP_FORM_ADD || $currentstep == $STEP_FORM_MODIFY)
  {
		if ($isonlyoneemployee == 1)
		{
			echo '<tr><td>' . d_trad('name:') . '</td><td>' . $employeeA[$employeeid] . '</td></tr>';
			echo '<input type=hidden name="employeeid" value="' . $employeeid. '">';      
		}
		else
		{
			$dp_isform=0;require('hr/chooseemployee.php');
		}
    
    echo '<tr><td>' . d_trad('date:') . '</td><td>';
    $datename = 'date'; $dp_datepicker_min=1920; if ($currentstep == $STEP_FORM_MODIFY) { $selecteddate=$row[0]['date'];}
    require('inc/datepicker.php');
    echo '</td></tr>';
    echo '<tr><td style="vertical-align:top;">' . d_trad('comment:') . '</td>';
    echo '<td><textarea name="comment" rows=30 cols=80>';
    if ($currentstep == $STEP_FORM_MODIFY) { echo d_input($row[0]['comment']); }
    echo '</textarea></td></tr>';
    
    echo '<tr><td style="vertical-align:top;">' . d_trad('disciplinaryfilelevel:') . '</td>'; 
    $level = 0;
    if ($currentstep == $STEP_FORM_MODIFY) { $level = $row[0]['level'];}
    echo '<td><select name="level">';
    $selected = ''; if ($level == $DISCIPLINARY_FILE_WARNING) { $selected = ' SELECTED'; }    
    echo '<option value=' . $DISCIPLINARY_FILE_WARNING . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_WARNING) . '</option>'; 
    $selected = ''; if ($level == $DISCIPLINARY_FILE_BLAME) { $selected = ' SELECTED'; }       
    echo '<option value=' . $DISCIPLINARY_FILE_BLAME . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_BLAME) . '</option>';    
    $selected = ''; if ($level == $DISCIPLINARY_FILE_NOTIFICATION) { $selected = ' SELECTED'; }     
    echo '<option value=' . $DISCIPLINARY_FILE_NOTIFICATION . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_NOTIFICATION) . '</option>';
    $selected = ''; if ($level == $DISCIPLINARY_FILE_DISMISSAL1) { $selected = ' SELECTED'; }    
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL1 . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL1) . '</option>'; 
    $selected = ''; if ($level == $DISCIPLINARY_FILE_DISMISSAL2) { $selected = ' SELECTED'; }       
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL2 . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL2) . '</option>';    
    $selected = ''; if ($level == $DISCIPLINARY_FILE_DISMISSAL3) { $selected = ' SELECTED'; }     
    echo '<option value=' . $DISCIPLINARY_FILE_DISMISSAL3 . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DISMISSAL3) . '</option>';
    $selected = ''; if ($level == $DISCIPLINARY_FILE_SUSPENSION) { $selected = ' SELECTED'; }    
    echo '<option value=' . $DISCIPLINARY_FILE_SUSPENSION . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_SUSPENSION) . '</option>'; 
    $selected = ''; if ($level == $DISCIPLINARY_FILE_TRANSFER) { $selected = ' SELECTED'; }       
    echo '<option value=' . $DISCIPLINARY_FILE_TRANSFER . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_TRANSFER) . '</option>';    
    $selected = ''; if ($level == $DISCIPLINARY_FILE_OBSERVATION) { $selected = ' SELECTED'; }     
    echo '<option value=' . $DISCIPLINARY_FILE_OBSERVATION . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_OBSERVATION) . '</option>';
    $selected = ''; if ($level == $DISCIPLINARY_FILE_DOWNGRADING) { $selected = ' SELECTED'; }     
    echo '<option value=' . $DISCIPLINARY_FILE_DOWNGRADING . ' ' . $selected . '>' . d_trad('disciplinaryfilelevel' . $DISCIPLINARY_FILE_DOWNGRADING) . '</option>';

    echo '</select></td></tr>';

    if ( $currentstep == $STEP_FORM_MODIFY)  
    {    
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1></td></tr>';    
      echo '<input type=hidden name="employeedisciplinaryfileid" value="' . $employeedisciplinaryfileid . '">';    
    }
  }
} 
if ($currentstep != $STEP_FORM_EMPLOYEE && $currentstep != $STEP_FORM_ADD)
{
  if ($isonlyoneemployee == 1)
  {
    echo '<input type=hidden name="employeeid" value="' . $employeeid. '">';
  }
  else
  {
    echo '<input type=hidden name="employeeid" value="' . $employeeid_posted. '">';
  }
}?>
</table>
<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
if (1==1)
{
  if ($currentstep == $STEP_FORM_EMPLOYEE)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  }
  if ($currentstep == $STEP_FORM || $currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_ADD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_MODIFY)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  } 
}
?>
</table>
</form>