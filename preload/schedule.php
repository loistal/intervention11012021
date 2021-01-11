<?php
# load $scheduleA[$scheduleid] : 
#$isdisplaygroupname = if there is a group of schedule: we show only the first schedule of the group with the name of the group   
if (!isset($isdisplaygroupname)) { $isdisplaygroupname = 1; }
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];

unset($scheduleA);
$query = 'select * from schedule ';
if(!$ds_showdeleteditems)
{
  $query .= ' where deleted = 0';
}  
$query .= ' order by schedulegroupid,schedulename';
$query_prm = array();
require('inc/doquery.php');

$schedulegroupid = ''; $schedulegroupid_prev = ''; 
for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
{
  $istobeadded = 1;
  $isgroupname = 0;
  $scheduleid_temp = (int) ($query_result[$kladd_i]['scheduleid']+0);
  $schedulegroupid = $query_result[$kladd_i]['schedulegroupid'] +0;

  #if there is a group of schedule: we show only the first schedule of the group with the name of the group  
  if ($isdisplaygroupname)
  {
    if($schedulegroupid != 0)
    {
      if($schedulegroupid != $schedulegroupid_prev)
      {
        $isgroupname = 1;
        $schedulegroupname = $schedule_groupnameA[$scheduleid];        
        $select .= '<option value="' . $scheduleid . '"' . $selected . '>' . $schedulegroupname .'</option>';
      }
      else
      {
        $istobeadded = 0;
      }
    }
    else
    {
      $select .= '<option value="' . $scheduleid . '"' . $selected . '>' . $schedulename .'</option>';
    }
    $schedulegroupid_prev = $schedulegroupid;  
  }
  
  if ($istobeadded)
  {
    if ( $isgroupname )
    {
      $scheduleA[$scheduleid_temp] = $query_result[$kladd_i]['schedulegroupname'];      
    }
    else
    {
      $scheduleA[$scheduleid_temp] = $query_result[$kladd_i]['schedulename'];
    }
    $schedule_groupidA[$scheduleid_temp] = $schedulegroupid;   
    $schedule_groupnameA[$scheduleid_temp] = $query_result[$kladd_i]['schedulegroupname']; 
    $schedule_periodicA[$scheduleid_temp] = $query_result[$kladd_i]['periodic']; 
    $schedule_periodicspecA[$scheduleid_temp] = $query_result[$kladd_i]['periodic_spec'];  
    $schedule_schedulestartA[$scheduleid_temp] = $query_result[$kladd_i]['schedulestart'];     
    $schedule_schedulestopA[$scheduleid_temp] = $query_result[$kladd_i]['schedulestop'];   

    #fill $ds_planningteamnbvalues * 7 days of array: like $schedule_valueidA[$scheduleid][$iday][$ivalue]
    $schedule_valueidA[$scheduleid_temp][1][1] = $query_result[$kladd_i]['valueid_day1_1']+0;
    $schedule_valueidA[$scheduleid_temp][2][1] = $query_result[$kladd_i]['valueid_day2_1']+0;
    $schedule_valueidA[$scheduleid_temp][3][1] = $query_result[$kladd_i]['valueid_day3_1']+0;
    $schedule_valueidA[$scheduleid_temp][4][1] = $query_result[$kladd_i]['valueid_day4_1']+0;
    $schedule_valueidA[$scheduleid_temp][5][1] = $query_result[$kladd_i]['valueid_day5_1']+0;
    $schedule_valueidA[$scheduleid_temp][6][1] = $query_result[$kladd_i]['valueid_day6_1']+0;
    $schedule_valueidA[$scheduleid_temp][7][1] = $query_result[$kladd_i]['valueid_day7_1']+0;

    $schedule_valueidA[$scheduleid_temp][1][2] = $query_result[$kladd_i]['valueid_day1_2']+0;
    $schedule_valueidA[$scheduleid_temp][2][2] = $query_result[$kladd_i]['valueid_day2_2']+0;
    $schedule_valueidA[$scheduleid_temp][3][2] = $query_result[$kladd_i]['valueid_day3_2']+0;
    $schedule_valueidA[$scheduleid_temp][4][2] = $query_result[$kladd_i]['valueid_day4_2']+0;
    $schedule_valueidA[$scheduleid_temp][5][2] = $query_result[$kladd_i]['valueid_day5_2']+0;
    $schedule_valueidA[$scheduleid_temp][6][2] = $query_result[$kladd_i]['valueid_day6_2']+0;
    $schedule_valueidA[$scheduleid_temp][7][2] = $query_result[$kladd_i]['valueid_day7_2']+0;
    
    $schedule_valueidA[$scheduleid_temp][1][3] = $query_result[$kladd_i]['valueid_day1_3']+0;
    $schedule_valueidA[$scheduleid_temp][2][3] = $query_result[$kladd_i]['valueid_day2_3']+0;
    $schedule_valueidA[$scheduleid_temp][3][3] = $query_result[$kladd_i]['valueid_day3_3']+0;
    $schedule_valueidA[$scheduleid_temp][4][3] = $query_result[$kladd_i]['valueid_day4_3']+0;
    $schedule_valueidA[$scheduleid_temp][5][3] = $query_result[$kladd_i]['valueid_day5_3']+0;
    $schedule_valueidA[$scheduleid_temp][6][3] = $query_result[$kladd_i]['valueid_day6_3']+0;
    $schedule_valueidA[$scheduleid_temp][7][3] = $query_result[$kladd_i]['valueid_day7_3']+0;
  }
  #debug
  /*for ($iday=1;$iday<=7;$iday++)
  {
    for ($v=1;$v<=3;$v++)
    {
      $temp = $schedule_valueidA[$scheduleid_temp][$iday][$v];
      d_debug('schedule_valueidA['. $scheduleid_temp . '][' . $iday . '][' . $v . ']',$temp);
    }
  }*/
}
?>