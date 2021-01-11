<?php
require('preload/planningteamvalue.php'); 
require('preload/color.php'); 

$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];
$ds_showdeleteditems = $_SESSION['ds_showdeleteditems'];

$title = d_trad('schedules');
echo '<h2>' . $title . '</h2>';
#reload each time because there is an option whose value can be different
$isdisplaygroupname = 0;require('preload/schedule.php');

if (count($scheduleA) > 0)
{
  echo '<form method="post" action="hr.php?hrmenu=addschedule">';
  echo '<table class="report">';
  echo '<thead><th>' . d_trad('group') . '</th>';  
  echo '<th>' . d_trad('name') . '</th>';
  for($iday=1;$iday<=7;$iday++)
  {
    echo '<th colspan=' . $ds_planningteamnbvalues . '>' . d_trad('dayofweek' . $iday) . '</th>';
    #day seperation
    echo '<th style="background-color:#ffffff;">&nbsp;</th>';
  }
  echo '<th>' . d_trad('periodic') . '</th>';
  /*echo '<th>' . d_trad('validity') . '</th>';*/

  if($ds_showdeleteditems)
  {
    echo '<th>' . d_trad('deleted') . '</th>';
  }
  echo '</thead>';
  #2nd line of title with planningteamvalue
  echo '<thead><th></th><th></th>';
  for($iday=1;$iday<=7;$iday++)
  {
    for($i=1;$i<=$ds_planningteamnbvalues;$i++)
    {
      $ds_term_planningteamvalue = 'ds_term_planningteamvalue' . $i;
      $$ds_term_planningteamvalue= $_SESSION[$ds_term_planningteamvalue];        
      echo '<th>' . $$ds_term_planningteamvalue . '</th>';
    }
    #day seperation
    echo '<td style="background-color:#ffffff;">&nbsp;</td>';
  }
  echo '<th></th>';
  if($ds_showdeleteditems)
  {
    echo '<th></th>';
  }
  echo '</thead>';
  foreach($scheduleA as $scheduleid=>$schedulename)
  { 
    echo d_tr();
    echo '<td>' . d_output($schedule_groupnameA[$scheduleid]) . '</td>';
    echo '<td><a href="hr.php?hrmenu=modschedule&step=1&scheduleid=' . $scheduleid . '">' . d_output($schedulename) . '</td>';
    
    for($iday=1;$iday<=7;$iday++)
    {
      for($value=1;$value<=$ds_planningteamnbvalues;$value++)
      {            
        $planningteamvalueid = $schedule_valueidA[$scheduleid][$iday][$value];  
        $colorid = $planningteamvalue_coloridA[$planningteamvalueid];
        $colorcode = $color_codeA[$colorid];        
        echo '<td style="text-align:center;background-color:#' .$colorcode . '" >' . $planningteamvalue_symbolA[$planningteamvalueid] . '</td>';
      }
      #day seperation      
      echo '<td style="background-color:#ffffff;">&nbsp;</td>';
    }
    
    $periodic = $schedule_periodicA[$scheduleid];
    $periodic_spec = $schedule_periodicspecA[$scheduleid];   
    
    echo '<td>';
    if ($periodic == 1)
    {
      $periodic_display = '';
      if ($periodic_spec == 0) { $periodic_display = 'allweeks'; }
      else { $periodic_display = 'periodic_spec_weekly_' . $periodic_spec; }
      echo d_trad($periodic_display);
    }    
    echo '</td>';
    /*echo '<td>';
    if ($schedule_schedulestartA[$scheduleid] != '')
    {
      echo datefix2($schedule_schedulestartA[$scheduleid]);
    }
    if ($schedule_schedulestopA[$scheduleid] != '')
    {
      echo ' &nbsp; ' . d_trad('validity_to') . ' &nbsp; ' . datefix2($schedule_schedulestopA[$scheduleid]);  
    }
    echo '</td>';*/
    if ($ds_showdeleteditems)
    {
      echo '<td align=center>';
      if ($row['deleted']) { echo '&radic;'; }
      echo '</td>';
    }
    echo '</tr>';
  }
  echo '</table>';
  echo '<br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';    
  echo '</form>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}

?>