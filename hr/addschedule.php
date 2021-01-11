<?php
unset($planningteamvalueA);$hr_orderby_presence=1; require('preload/planningteamvalue.php');
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];

switch($currentstep)
{
  # form
  case 0:
  
    echo '<h2>' . d_trad('addschedule') . '</h2>'; 
    echo '<form method="post" action="hr.php"><table>';
    #nb col by day
    $nbcol = $ds_planningteamnbvalues * 2 +1;
    echo '<tr><td>' . d_trad('name:') . '</td><td colspan=' . $nbcol . '><input type="text" name="schedulename" size=100></td></tr>';
   
    echo '<tr><td>' . d_trad('type:') .'</td>';
    echo '<td><input type=radio name=periodic value=1 checked></td>';
    echo '<td>' .d_trad('weekly') . '</td><td colspan=' . ($nbcol-1) . '>';
    echo '<select name="periodic_spec">';
    #Every week
    echo '<option value=0 selected>'.d_trad('allweeks') .'</option>';
    #Every odd week
    echo '<option value=1>'.d_trad('periodic_spec_weekly_1').'</option>';
    #Every even week
    echo '<option value=2>'.d_trad('periodic_spec_weekly_2').'</option>';
    #Every 1st week of month
    echo '<option value=3>'.d_trad('periodic_spec_weekly_3').'</option>';
    #Every 2nd week of month
    echo '<option value=4>'.d_trad('periodic_spec_weekly_4').'</option>';
    #Every 3rd week of month
    echo '<option value=5>'.d_trad('periodic_spec_weekly_5').'</option>';
    #Every 4th week of month
    echo '<option value=6>'.d_trad('periodic_spec_weekly_6').'</option>';
    echo '</select>';
    echo '</td></tr>';
    
    /*echo '<tr><td></td>';
    echo '<td><input type=radio name=periodic value=0></td>'; 

    echo '<td>' . d_trad('punctual') . '</td><td colspan=' . ($nbcol-1) . '>';
    $datename = 'schedulestart'; $selecteddate = $$datename;
    require('inc/datepicker.php');
    echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
    $datename = 'schedulestop'; $selecteddate = $$datename;
    require('inc/datepicker.php');
    echo '</td></tr>';
    
    echo '<tr><td colspan= ' . ($nbcol + 1) . '>&nbsp;</td></tr>';*/
    
    echo '<tr><td>' . d_trad('group:') .'</td><td><input type=radio name=schedulegroupchoice value=-1 checked></td><td colspan=' . ($nbcol-2) . '>' . d_trad('none') . '</td></tr>';
    echo '<tr><td></td><td><input type=radio name=schedulegroupchoice value=0></td><td colspan=' . ($nbcol-2) . '>' . d_trad('existing') . '&nbsp;&nbsp;';
    #get the different groups of schedules
    echo '<select name=schedulegroupid>';
    $query = 'select distinct schedulegroupid,schedulegroupname from schedule where schedulegroupid > 0 order by schedulegroupname';
    $query_prm = array();
    require('inc/doquery.php');
    for($i=0;$i<$num_results;$i++)
    {
      echo '<option value="' . $query_result[$i]['schedulegroupid'] . '">' . d_input($query_result[$i]['schedulegroupname']) . '</option>';
    }
    echo '</select></td></tr>';  
    echo '<tr><td></td><td><input type=radio name=schedulegroupchoice value=1></td><td colspan=' . ($nbcol-2) . '>' . d_trad('new') . '&nbsp;&nbsp;<input name=schedulegroupname type=text size=20></td></tr>'; 
    
    echo '<tr><td colspan= ' . ($nbcol + 1) . '>&nbsp;</td></tr>';
    
    echo '<tr><td></td><td>';
    for($iday=1;$iday<=7;$iday++)
    {
      echo '<tr><td>' . d_trad('dayofweek' . $iday . ':') . '</td>';
      for($i=1;$i<=$ds_planningteamnbvalues;$i++)
      {
        $ds_term_planningteamvalue = 'ds_term_planningteamvalue' . $i;
        $$ds_term_planningteamvalue= $_SESSION[$ds_term_planningteamvalue];        
        echo '<td>&nbsp;&nbsp;' . $$ds_term_planningteamvalue . ':&nbsp;</td>';
        $selectname = 'valueid_day' . $iday . '_' . $i;
        echo '<td><select name="' . $selectname . '">';
        foreach($planningteamvalueA as $planningteamvalueid=>$planningteamvaluename)
        {
          echo '<option value="' . $planningteamvalueid . '">' . $planningteamvaluename . '</option>';
        }
        echo '</select></td>';
      }
      echo '</tr>';
    }
    echo '<tr><td colspan="' . ($nbcol +1) .'" align="center">';
    echo '<input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="' . $hrmenu . '">';
    echo '<input type="submit" value="' . d_trad('validate') . '"></td></tr>';
    echo '</table></form>';

    break;

  # action
  case 1:
    $schedulename = d_input($_POST['schedulename']);
    $schedulegroupchoice = $_POST['schedulegroupchoice'] +0;    
    $schedulegroupid = $_POST['schedulegroupid']+0;    
    $schedulegroupname = d_input($_POST['schedulegroupname']);     
    $periodic = $_POST['periodic'] +0;
    $periodic_spec = $_POST['periodic_spec'] +0;
    $schedulestart = $_POST['schedulestart'];
    $schedulestop = $_POST['schedulestop'];
        
    if ($schedulename == '') 
    {
      echo '<p class="alert">' . d_trad('schedulenamemustnotbeempty',array($schedulename)) . '<p>';    
    }
    else
    {
      #check if name already exists
      $query = 'select * from schedule where schedulename=? and deleted=0';
      $query_prm = array($schedulename);
      require('inc/doquery.php');
      if ($num_results > 0)
      {
         echo '<p class="alert">' . d_trad('schedulenamealreadyexists',array($schedulename)) . '<p>';
      }
      else
      {
        $query_insert = 'insert into schedule (schedulename,periodic,periodic_spec';
        $query_prm_insert = array($schedulename,$periodic,$periodic_spec);
        $queryvalues = ') values (?,?,?';
        if($periodic == 0)
        {
          $query_insert .= ',schedulestart,schedulestop';
          array_push($query_prm_insert,$schedulestart,$schedulestop);
          $queryvalues .= ',?,?';
        }
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

        $query_insert .= ',schedulegroupid,schedulegroupname';
        $queryvalues .= ',?,?';
        array_push($query_prm_insert,$schedulegroupid,$schedulegroupname);

        for($iday=1;$iday<=7;$iday++)
        {
          for($i=1;$i<=$ds_planningteamnbvalues;$i++)
          {
            $selectname = 'valueid_day' . $iday . '_' . $i;
            $$selectname = $_POST[$selectname];
            $query_insert .= ',' . $selectname;
            $queryvalues .= ',?';
            array_push($query_prm_insert,$$selectname);       
          }
        }
        $query_insert .= $queryvalues . ')';
        $query = $query_insert; $query_prm = $query_prm_insert;
        require('inc/doquery.php');
        if($num_results > 0)
        {
          echo '<p>' . d_trad('scheduleadded',$schedulename) .'</p>';
        }
        require('hr/listschedule.php');
      }
    }
    break;
}
?>