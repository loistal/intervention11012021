<?php

# TODO IMPORTANT replace into => on duplicate key update

require('preload/employee.php');

if (!isset($travelexpensetypeA)) { require('preload/travelexpensetype.php'); }

$STEP_FORM_EXP_TRAVELEXP = 1;
$STEP_FORM_EXP = 2;
$STEP_FORM_EXP_ADD = 3;
$STEP_FORM_EXP_MODIFY = 4;
$STEP_FORM_EXP_VALIDATE_ADD = 5;
$STEP_FORM_EXP_VALIDATE_MOD = 6;

$STEP_FORM_EXPITEM_TRAVELEXP = 1;
$STEP_FORM_EXPITEM = 2;
$STEP_FORM_EXPITEM_ADD = 3;
$STEP_FORM_EXPITEM_MODIFY = 4;
$STEP_FORM_EXPITEM_VALIDATE_ADD = 5;
$STEP_FORM_EXPITEM_VALIDATE_MOD = 6;

$NUM_STATES = 3;
$STATE_SAVED = 0;
$STATE_ACCEPTED = 1;
$STATE_PROCESSED = 2;

$TEAMIMANAGE = 'TEAMIMANAGE';
$ALL = 'ALL';

$MAX_LENGTH_DISPLAYED = 60;

if ( $_POST['submitformte'] == d_trad('add') ) { $currentstep =  $STEP_FORM_EXP_ADD;}
if ($currenstep == 0 && $currentstepitem > 0){ $currentstep = $STEP_FORM_EXP_MODIFY; } 

if ($currentstepitem != $STEP_FORM_EXPITEM_ADD && $currentstepitem != $STEP_FORM_EXPITEM_MODIFY)
{
  #title
  switch ($currentstep)
  {
    case $STEP_FORM_EXP:
    case $STEP_FORM_EXP_VALIDATE_MOD:  
    case $STEP_FORM_EXP_VALIDATE_ADD:
      echo '<h2>' . d_trad('travelexpense') . '</h2>';
      break;
      
    case $STEP_FORM_EXP_ADD:
      echo '<h2>' . d_trad('addtravelexpense') . '</h2>';      
      break;   

    case $STEP_FORM_EXP_MODIFY:
      echo '<h2>' . d_trad('modifytravelexpense') . '</h2>';
      $travelexpenseid = $_GET['travelexpenseid'] +0 ;
      if ( $travelexpenseid == 0 ){ $travelexpenseid =  $_POST['travelexpenseid'];}
      break;       
  }

  $numrows = 0;
  # Form to choose wich kind of travelexpense
  if ($currentstep == $STEP_FORM_EXP_TRAVELEXP)
  {
    echo '<h2>' . d_trad('travelexpense') . '</h2>'; ?>
    <form method="post" action="hr.php" name=formexp><table>

    <tr><td><?php echo d_trad('startdate:'); ?></td>
    <td><?php $datename = 'startdate'; require('inc/datepicker.php');?></td></tr>
    
    <tr><td><?php echo d_trad('stopdate:'); ?></td>
    <td><?php $datename = 'stopdate'; require('inc/datepicker.php');?></td></tr>   
    
    <tr><td><?php echo d_trad('state:');?></td><td><select name=state><?php
      echo '<option value=-1>' . d_trad('selectall') . '</option>';  
      for($st=0;$st<$NUM_STATES;$st++)
      {
        echo '<option value=' . $st . '>' . d_trad('travelexpensestate' .$st) . '</option>';
      }?></select></td></tr>

    <?php
    /*
    if(1==1)
    {
      $dp_isform = 0;
      require('hr/chooseemployee.php');
    }  
*/
    ?>
   
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="<?php echo $STEP_FORM_EXP;?>"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
    <input type="submit" name="submitformte" value="<?php echo d_trad('add');?>"> 
    <input type="submit" name="submitformte" value="<?php echo d_trad('list');?>">
    </td></tr>
    </table></form><?php
  }
  else if ($currentstep == $STEP_FORM_EXP_VALIDATE_ADD || $currentstep == $STEP_FORM_EXP_VALIDATE_MOD)
  {
    # save
    if ($currentstep == $STEP_FORM_EXP_VALIDATE_MOD) { $travelexpenseid = $_POST['travelexpenseid'] +0;} 
    $travelexpensedescr = $_POST['travelexpensedescr'];

      $employeeid = $_POST['employeeid'];  

    $datename = 'startdate'; require('inc/datepickerresult.php');   
    $datename = 'stopdate'; require('inc/datepickerresult.php'); 
    $state = $_POST['state'] + 0;  
    $comment = $_POST['comment'];  
    $deleted = $_POST['deleted'] + 0;  
    
    $query = 'REPLACE INTO travelexpense (travelexpenseid,travelexpensedescr,employeeid,startdate,stopdate,state,comment,deleted) values (?,?,?,?,?,?,?,?)';
    $query_prm = array($travelexpenseid,$travelexpensedescr,$employeeid,$startdate,$stopdate,$state,$comment,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      $travelexpenseid = $query_insert_id;
      if ($currentstep == $STEP_FORM_EXP_VALIDATE_ADD)
      {
        echo '<p>' . d_trad('travelexpenseadded',$travelexpensedescr) . '</p><br>';   
      }
      else
      {
        echo '<p>' . d_trad('travelexpensemodified',$travelexpensedescr) . '</p><br>';      
      }
    }  
  }
    
  if ( $currentstep > $STEP_FORM_EXP_TRAVELEXP)
  {
    if ($currentstep != $STEP_FORM_EXP_ADD)
    {
      # pre-filled form 
      if(1==1)
      {
        $employeeid = $_POST['employeeid'];  
        require('hr/chooseemployeewithteams.php');        
      }
      else
      {
        $employee_todisplayA[0]['employeeid'] = $ds_myemployeeid;
        $nbemployees = 1;
      }        
    
      $query = 'select * from travelexpense te';
      if ($employeeid == $TEAMIMANAGE) 
      {  
        $query .= ',employee e';
      }
      $query .= ' where';
      $query_prm = array();
      
      if ( $currentstep == $STEP_FORM_EXP)
      {
        $travelexpenseid = $_POST['travelexpenseid'] + 0; 

        $datename = 'startdate'; require('inc/datepickerresult.php');   
        $datename = 'stopdate'; require('inc/datepickerresult.php'); 
        $state = $_POST['state'] + 0;        
        $deleted = $_POST['deleted'] + 0;     
      
        if ($travelexpenseid > 0)
        {
          $query .= ' te.travelexpenseid=? and';
          array_push($query_prm,$travelexpenseid);
        }
/*
        $query .= ' te.employeeid in ( '; 
        for($e=0;$e<$nbemployees;$e++)
        {
          $query .= '?';
          if ($e < $nbemployees -1 ) { $query .= ','; }
          array_push($query_prm,$employee_todisplayA[$e]['employeeid']);
        }
        $query .= ') and';  */
        if ($startdate > 0)
        {
          $query .= ' te.startdate>=? and';
          array_push($query_prm,$startdate);
        }
        if ($stopdate > 0)
        {
          $query .= ' te.stopdate<=? and';
          array_push($query_prm,$stopdate);
        }   
        if ($state >= 0)
        {
          $query .= ' te.state=? and';
          array_push($query_prm,$state);
        }  
        if ($ds_showdeleteditems != 1)
        {
          $query .= ' te.deleted=0';
        }
        if ($employeeid == $TEAMIMANAGE) 
        {  
          $query .= ' and e.employeeid = te.employeeid';
        }        
        #if query endswith 'where' : delete it
        #else delete 'and'
        $poswhere = strripos($query,'where');
        $posand = strripos($query,'and');
        if ( $poswhere == (strlen($query) - strlen('where')))
        {
          $query = substr($query,0,$poswhere);
        }
        else if ( $posand == (strlen($query) - strlen('and')))
        {
          $query = substr($query,0,$posand);
        }
      }
      else
      {
        $query .= ' travelexpenseid=?';
        $query_prm = array($travelexpenseid);
      }

      require ('inc/doquery.php');
      $numrows = $num_results;
      if ($numrows > 0 )
      {
        $row = $query_result;  
      }
    }

    ?>
    <form method="post" action="hr.php">
    <?php 
    if ( $numrows == 0 && ($currentstep != $STEP_FORM_EXP_ADD))
    { 
      echo '<p>' . d_trad('noresult') . '</p>';
    }
    else
    {    
      if ( $currentstep == $STEP_FORM_EXP)
      {?>
        <table class="report">
        <thead>
          <th><?php echo d_trad('travelexpensedescr'); ?></th>   
          <th><?php echo d_trad('startdate'); ?></th> 
          <th><?php echo d_trad('stopdate'); ?></th>
          <?php if(1==1)
          {
            echo '<th>' . d_trad('employee') . '</th>'; 
          }
 
          if (1==1)
          {?>
            <th><?php echo d_trad('state'); ?></th><?php
          }?>
          <th><?php echo d_trad('comment'); ?></th>           
          <?php
          if(( $ds_showdeleteditems  || $currentstep == $STEP_FORM_EXP_MODIFY) && $currentstep != $STEP_FORM_EXP_ADD)
          {
            echo '<th>' . d_trad('deleted') . '</th>';
          } ?>
        </thead><?php 
      }
      else
      {
        echo '<table>';
      }
    }
    
    if ( $currentstep ==  $STEP_FORM_EXP)
    {
      for ($r=0;$r<$numrows;$r++)
      {
        $travelexpenseid = $row[$r]['travelexpenseid'];
        $travelexpensedescrdisplayed = d_output($row[$r]['travelexpensedescr']);      
        $commentdisplayed = $row[$r]['comment'];      
        $employeeid = $row[$r]['employeeid'];
        $state = $row[$r]['state'] + 0;
        
        $href = 'hr.php?hrmenu=travelexpenses&step=' . $STEP_FORM_EXP_MODIFY . '&travelexpenseid=' . $travelexpenseid;
        echo d_tr();   
        
        if ( strlen($travelexpensedescrdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $travelexpensedescrdisplayed = substr($travelexpensedescrdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
        echo '<td><a href="' . $href . '">' . $travelexpensedescrdisplayed . '</a></td>'; 
        
        echo '<td><a href="' . $href . '">' . datefix2($row[$r]['startdate']) . '</a></td>';
        echo '<td><a href="' . $href . '">' . datefix2($row[$r]['stopdate']) . '</a></td>';  
        if(1==1)
        {
          echo '<td><a href="' . $href . '">' . $employeeA[$employeeid] . '</a></td>';   
        }
        echo '<td><a href="' . $href . '">' . d_trad('travelexpensestate'.$state) . '</a></td>'; 

        if ( strlen($commentdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $commentdisplayed = substr($commentdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
        echo '<td><a href="' . $href . '">' . $commentdisplayed . '</a></td>';         
        
        if ($currentstep != $STEP_FORM_EXP_ADD && $ds_showdeleteditems)
        {
          echo '<td align=center><a href="' . $href . '">';
          if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
          echo '</a></td>';
        }
        echo '</tr>';     
      }
      echo '</table>';
    }
    else if ($numrows > 0 || $currentstep == $STEP_FORM_EXP_ADD )
    {
      $comment = d_input($row[0]['comment']);
      $state = $row[0]['state'];
      $travelexpensedescr = d_input($row[0]['travelexpensedescr']);?>
      <tr><td><?php echo d_trad('travelexpensedescr:'); ?></td>
      <td><input type=text name=travelexpensedescr value="<?php echo $travelexpensedescr; ?>"></td></tr>
      <tr><td><?php echo d_trad('startdate:'); ?></td>
      <td><?php $datename = 'startdate'; $selecteddate=$row[0]['startdate']; require('inc/datepicker.php');?></td></tr>
    
      <tr><td><?php echo d_trad('stopdate:'); ?></td>
      <td><?php $datename = 'stopdate'; $selecteddate=$row[0]['stopdate']; require('inc/datepicker.php');?></td></tr>   
      
      <?php if($currentstep != $STEP_FORM_EXP_ADD)
      {?>
        <tr><td><?php echo d_trad('state:');?></td><td>
        <?php if(1==1) 
        {
          echo '<select name=state>';
          for($st=0;$st<$NUM_STATES;$st++)
          {
            $selected = '';
            if ($st == $state){ $selected = ' SELECTED'; }
            echo '<option value=' . $st . $selected .'>' . d_trad('travelexpensestate' .$st) . '</option>';
          }
          echo '</select>';
        }
        else
        {
          $state = $row[0]['state'] + 0;
          echo d_trad('travelexpensestate' . $state);
        }
        echo '</td></tr>';
      }
      
      if(1==1)
      {
        echo '<tr><td>Employé(e) :';
        #$dp_selectedid = $employeeid;
        $dp_selectedid = $row[0]['employeeid'];
        if (!$_SESSION['ds_ishrsuperuser']) { $dp_groupid = $_SESSION['ds_teamid']; }
        $dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
        /*
        echo '<tr><td>' . d_trad('employee:') . '</td>';
        echo '<td><select name="employeeid">';
 
        foreach($employeeimanageA as $eid=>$ename)
        {
          $selected = '';
          if ($eid == $ds_myemployeeid)
          {
            $selected = ' SELECTED';
          }
          echo '<option value=' .$eid . ' ' . $selected . '>' . $ename. '</option>';
        }
        echo '</select></td>';
        echo '</tr>';  
*/        
      }  
      
      echo '<tr><td>' . d_trad('comment:') . '</td>';
      echo '<td><textarea cols=120 rows=1 name=comment>' . d_input($comment) . '</textarea></tr>';      
      if ($currentstep != $STEP_FORM_EXP_ADD)
      {
        echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
        if ($row[0]['deleted']) { echo ' checked '; }
        echo '></td></tr>';
        echo '<input type=hidden name="travelexpenseid" value=' . $travelexpenseid .'>';
      }

      echo '</table>';    
    }
  } ?>

  <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type=hidden name="numrows" value="<?php echo $numrows; ?>">
  <?php
  if ($currentstep == $STEP_FORM_EXP )
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_EXP_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_EXP_ADD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_EXP_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_EXP_MODIFY)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_EXP_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
  else if ($currentstep == $STEP_FORM_EXP_VALIDATE_ADD || $currentstep == $STEP_FORM_EXP_VALIDATE_MOD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_EXP_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
  ?>
  </table>
  </form><?php
  if ($currentstepitem != $STEP_FORM_EXPITEM_VALIDATE_ADD && $currentstepitem != $STEP_FORM_EXPITEM_VALIDATE_MOD)
  {
    $currentstepitem = $STEP_FORM_EXPITEM;
  }
}

if ( $currentstep != $STEP_FORM_EXP_TRAVELEXP && $currentstep != $STEP_FORM_EXP && $currentstep != $STEP_FORM_EXP_ADD )
{
  require('hr/travelexpenseitems.php');
}
?>