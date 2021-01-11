<?php
unset($planningteamvalueA);$hr_orderby_presence=1; require('preload/planningteamvalue.php');
#reload each time because there is an option whose value can be different
$isdisplaygroupname = 0;require('preload/schedule.php');
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];
$ds_showdeleteditems = $_SESSION['ds_showdeleteditems'];

switch($currentstep)
{

  # form to choose wich schedule
  case 0:
    echo '<h2>' . d_trad('modifyschedule') . '</h2>';
    ?>
    <form method="post" action="hr.php"><table>
    <tr>
      <td><?php echo d_trad('name:');?></td>
      <td><select name="scheduleid">
        <?php
        foreach($scheduleA as $scheduleid => $schedulename)
        {
          echo '<option value="' . $scheduleid . '">' . d_output($schedulename) . '</option>';
        }
        ?>
      </select></td>
    </tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
    <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
    </table></form><?php
    break;

  # form to modify schedule
  case 1:
    $scheduleid = $_POST['scheduleid'];
    if ( !isset($_POST['scheduleid']) ) { $scheduleid = $_GET['scheduleid']; }
    $query = 'select * from schedule where scheduleid=?';
    if(!$ds_showdeleteditems) {  $query .= ' and deleted=0'; }
    $query_prm = array($scheduleid);
    require('inc/doquery.php');
    $row = $query_result[0]; 
    $schedulename = ''; if(!empty($row['schedulename'])){ $schedulename = $row['schedulename']; }
    
    if($num_results > 0)
    {
      $periodic = $row['periodic'];   
      $periodic_spec = $row['periodic_spec'];
      /*$schedulestart = $row['schedulestart'];
      $schedulestop = $row['schedulestop']; */ 
      $schedulegroupid = $row['schedulegroupid'] +0;
      $schedulegroupname = $row['schedulegroupname'];
      
      echo '<h2>' . d_trad('modifyschedule') . '</h2>'; 
      echo '<form method="post" action="hr.php"><table>';
      #nb col by day
      $nbcol = $ds_planningteamnbvalues * 2;
      echo '<tr><td>' . d_trad('name:') . '</td><td colspan=' . ($nbcol +1). '><input type="text" name="schedulename" value="' . d_output($schedulename) . '" size=100></td></tr>';
      
      echo '<tr><td>' . d_trad('type:') .'</td>';
      echo '<td><input type=radio name=periodic value=1 '; if( $periodic == 1 ) { echo ' checked'; }  echo '></td>';
      echo '<td>' .d_trad('weekly') . '</td><td colspan=' . ($nbcol-1) . '>';
      echo '<select name="periodic_spec">';

      #Every week
      echo '<option value=0'; if ( $periodic_spec == 0 ) { echo ' selected' ; } echo '>'.d_trad('allweeks') .'</option>';
      #Every odd week
      echo '<option value=1'; if ( $periodic_spec == 1 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_1').'</option>';
      #Every even week
      echo '<option value=2'; if ( $periodic_spec == 2 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_2').'</option>';
      #Every 1st week of month
      echo '<option value=3'; if ( $periodic_spec == 3 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_3').'</option>';
      #Every 2nd week of month
      echo '<option value=4'; if ( $periodic_spec == 4 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_4').'</option>';
      #Every 3rd week of month
      echo '<option value=5'; if ( $periodic_spec == 5 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_5').'</option>';
      #Every 4th week of month
      echo '<option value=6'; if ( $periodic_spec == 6 ) { echo ' selected' ; } echo '>'.d_trad('periodic_spec_weekly_6').'</option>';
      echo '</select>';
      echo '</td></tr>';
      
      /*echo '<tr><td></td>';
      echo '<td><input type=radio name=periodic value=0'; 
      if( $periodic == 0 ) { echo ' checked'; }
      echo '></td>';
      echo '<td>' . d_trad('punctual') . '</td><td colspan=' . ($nbcol-1) . '>';
      $datename = 'schedulestart'; $selecteddate = $$datename;
      require('inc/datepicker.php');
      echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
      $datename = 'schedulestop'; $selecteddate = $$datename;
      require('inc/datepicker.php');
      echo '</td></tr>';*/
      
      echo '<tr><td colspan= ' . ($nbcol + 1) . '>&nbsp;</td></tr>';

      echo '<tr><td>' . d_trad('group:') .'</td><td><input type=radio name=schedulegroupchoice value=-1';
      if ( $schedulegroupid  ==  NULL || $schedulegroupid  == 0) {echo ' checked'; } 
      echo '></td><td colspan=' . ($nbcol-2) . '>' . d_trad('none') . '</td></tr>';
      echo '<tr><td></td><td><input type=radio name=schedulegroupchoice value=0';
      if ( $schedulegroupid  > 0 ) {echo ' checked'; } 
      echo '></td><td colspan=' . ($nbcol-2) . '>' . d_trad('existing') . '&nbsp;&nbsp;';
      #get the different groups of schedules
      echo '<select name=schedulegroupid>';
      $query = 'select distinct schedulegroupid,schedulegroupname from schedule where schedulegroupid > 0 order by schedulegroupname';
      $query_prm = array();
      require('inc/doquery.php');
      for($i=0;$i<$num_results;$i++)
      {
        echo '<option value="' . $query_result[$i]['schedulegroupid'] . '"';
        if ( $schedulegroupid == $query_result[$i]['schedulegroupid']) { echo ' selected'; }
        echo '>' . d_output($query_result[$i]['schedulegroupname']) . '</option>';
      }
      echo '</select></td></tr>';  
      echo '<tr><td></td><td><input type=radio name=schedulegroupchoice value=1></td><td colspan=' . ($nbcol-2) . '>' . d_trad('new') . '&nbsp;&nbsp;<input name=schedulegroupname type=text size=20></td></tr>'; 
      
      echo '<tr><td colspan= ' . ($nbcol + 1) . '>&nbsp;</td></tr>';
    
      for($iday=1;$iday<=7;$iday++)
      {
        echo '<tr><td>' . d_trad('dayofweek' . $iday . '') . '</td>';
        for($i=1;$i<=$ds_planningteamnbvalues;$i++)
        {
          $ds_term_planningteamvalue = 'ds_term_planningteamvalue' . $i;
          $$ds_term_planningteamvalue= $_SESSION[$ds_term_planningteamvalue];        
          echo '<td>&nbsp;&nbsp;' . $$ds_term_planningteamvalue . ':&nbsp;</td>';
          $selectname = 'valueid_day' . $iday . '_' . $i;
          echo '<td><select name="' . $selectname . '">';
          foreach($planningteamvalueA as $planningteamvalueid=>$planningteamvaluename)
          {
            $selected = '';
            if($planningteamvalueid == $row[$selectname]) { $selected = ' SELECTED'; }
            echo '<option value="' . $planningteamvalueid . '"' . $selected .'>' . d_output($planningteamvaluename) . '</option>';
          }
          echo '</select></td>';
        }
        echo '</tr>';
      }
      echo '<tr><td colspan="' . ($nbcol +3) .'" align="center">';
      echo '<tr><td colspan= ' . ($nbcol + 1) . '>&nbsp;</td></tr>';
      echo '<tr><td>' . d_trad('deleted:') .'</td><td><input type="checkbox" name="deleted" value="1"';
      if ( $row['deleted'] ) { echo ' CHECKED';}
      echo '></td></tr>';
      echo '<tr><td colspan="' . ($nbcol +3) .'" align="center">';
      echo '<input type=hidden name="step" value="2">';
      echo '<input type=hidden name="scheduleid" value="' .$row['scheduleid'] . '">';
      echo '<input type=hidden name="hrmenu" value="' . $hrmenu . '">';
      echo '<input type="submit" value="' . d_trad('validate') . '"></td></tr>';
      echo '</table></form>';
    }
    else
    {
      echo d_trad('noresult');
    }
    break;

  #update schedule
  case 2:
    $scheduleid = $_POST['scheduleid'];
    $schedulename = d_input($_POST['schedulename']);
    $schedulegroupchoice = $_POST['schedulegroupchoice'];    
    $schedulegroupid = $_POST['schedulegroupid']+0;    
    $schedulegroupname = d_input($_POST['schedulegroupname']);     
    $periodic = $_POST['periodic'] +0;
    $periodic_spec = $_POST['periodic_spec'] +0;
    /*$schedulestart = $_POST['schedulestart'];
    $schedulestop = $_POST['schedulestop'];*/
    $deleted = $_POST['deleted'] +0;
      
    $query_update = 'update schedule set periodic=?,periodic_spec=?,deleted=?'; /*,schedulestart=?,schedulestop=? '; */
    $query_update_prm = array($periodic,$periodic_spec,$deleted);  
    /*if($periodic == 0)
    {
      array_push($query_update_prm,$schedulestart,$schedulestop);
    }
    else
    {
      array_push($query_update_prm,NULL,NULL);    
    }*/
        
    if($schedulename == '')
    {
      $err_schedulename_empty = 1;  
      echo '<p class="alert">' . d_trad('schedulenamemustnotbeempty') . '<p>';      
    }
    else
    {
      #check if schedule already exist with this name    
      $query = 'select * from schedule where schedulename=? and scheduleid<>? and deleted=0';
      $query_prm = array($schedulename,$scheduleid);
      require('inc/doquery.php');

      if ($num_results > 0)
      {
        echo '<p class="alert">' . d_trad('schedulenamealreadyexists',$schedulename) . '<p>';
      }
      else
      {
        $query_update .= ',schedulename=?';
        array_push($query_update_prm,$schedulename);
        
        if($schedulegroupchoice == 0)
        {
          #get the group name
          $query = 'select schedulegroupname from schedule where schedulegroupid = ?';
          $query_prm = array($schedulegroupid);
          require('inc/doquery.php');
          if ( $num_results > 0) { $schedulegroupname = $query_result[0]['schedulegroupname'];} 
        }
        elseif($schedulegroupchoice == 1)
        {
          #find max groupid to increment it for new one
          $query = 'select MAX(schedulegroupid) from schedule where schedulegroupid > 0';
          $query_prm = array();
          require('inc/doquery.php');
          $schedulegroupid = 1;
          if($num_results > 0) { $schedulegroupid = $query_result[0]['MAX(schedulegroupid)'] + 1; }
        }
        else
        {
          $schedulegroupid = 0;
        }
        
        $query_update .= ',schedulegroupid=?,schedulegroupname=?';
        array_push($query_update_prm,$schedulegroupid,$schedulegroupname);
        
        for($iday=1;$iday<=7;$iday++)
        {
          for($i=1;$i<=$ds_planningteamnbvalues;$i++)
          {
            $selectname = 'valueid_day' . $iday . '_' . $i ;
            $$selectname = $_POST[$selectname];
            //d_debug($selectname,$$selectname);

            $query_update .= ',' . $selectname . '=?';
            array_push($query_update_prm,$$selectname);       
          }
        }
        $query_update .= ' where scheduleid=?';
        array_push($query_update_prm,$scheduleid);
        $query = $query_update;
        $query_prm = $query_update_prm;
        require('inc/doquery.php');
        echo '<p>' . d_trad('schedulemodified',$schedulename) . '</p><br>';  
        require('hr/listschedule.php');
      }        
    }
    break;
}//switch

?>