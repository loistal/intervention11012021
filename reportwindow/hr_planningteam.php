<?php

# TODO IMPORTANT replace into => on duplicate key update

?>
<script language="JavaScript">
function toggle(source) {

    var node_list = document.getElementsByTagName('input');
    var checkboxes = [];
 
    for (var i = 0; i < node_list.length; i++) {
        var node = node_list[i];
 
        if (node.getAttribute('type') == 'checkbox') {
            checkboxes.push(node);
        }
    } 
 
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<script language="JavaScript">
function untoggle(source) {

    var node_list = document.getElementsByTagName('input');
    var checkboxes = [];
 
    for (var i = 0; i < node_list.length; i++) {
        var node = node_list[i];
 
        if (node.getAttribute('type') == 'checkbox') {
            checkboxes.push(node);
        }
    } 
 
  for(var i in checkboxes)
    checkboxes[i].checked = '';
}
</script>
<style>
.formplanning tr:hover,thead:hover td {
  background-color: #f8f8ff;
}
table.formplanning {
  border: 1px solid #000000;
  background: #b0c4de;
}
table.planning {
  background-color: white;
  border-spacing: 0px;
  display: inline;
}
form {
  padding: 0 em;
  border: 0px solid <?php echo $_SESSION['ds_fgcolor']; ?>;
  background: <?php echo $_SESSION['ds_formcolor']; ?>;
  -moz-border-radius: 0px;
  -webkit-border-radius: 0px;
  -opera-border-radius: 0px;
  -khtml-border-radius: 0px;
  border-radius: 0px;  
}
form.planning {
  background-color: white;
  padding: 0em;  
}
.planningbody {
  background-color: white;
  border: 1px solid #696969;
}
table.planning th{
  border: 1px solid #696969;
}
tr.planning {
  width: 100%
}
thead.planning { 
  display: table-header-group;
}
.noborder{
  border: 0px;
}
.borderleft{
  border-left: 1px solid #696969;
}
.withborder{
  border-left: 1px solid #696969;
  border-right: 1px solid #696969;
}
.planningempty
{
  border: 1px solid #696969;
}
.planningseparation
{
  border: 1px solid #696969;
  background-color: #ffffff;  
}
.planningtitle{
  background-color: #E0ECF8;
  border: 1px solid #696969;  
  font-weight: bold;
}
.planningtitlemonth{
  background-color: #E0ECF8;
  border: 1px solid #696969;  
  font-weight: bold;
}
.planningemployeemanager{
  border: 1px solid #696969; 
  text-align: left;  
  font-weight:bold;
}
.planningemployeenotmanager{
  border: 1px solid #696969; 
  text-align: left;  
}
.planningmanager{
  border: 1px solid #696969; 
  text-align: center; 
  font-weight: bold;
  font-size:14px;  
}
.planningnotmanager{
  border: 1px solid #696969;  
  text-align: center; 
  font-size:14px;
}

.planningnoresult{
  border: 1px solid #696969;  
  text-align: center;  
  color: red;
  font-size:14px;  
}

</style>

<?php
require('inc/func_planning.php');
if (!isset($employeesortedbyteamA)) {require ('preload/employeesortedbyteam.php');}
if (!isset($planningteamvalueA)){require ('preload/planningteamvalue.php');}
if (!isset($colorA)){require ('preload/color.php');}

#Global Variables
$ds_planningteamdayoff = $_SESSION['ds_planningteamdayoff'];
$ds_planningteamdayoffdisplayed = $_SESSION['ds_planningteamdayoffdisplayed'];
$ds_planningteamcommentcolumn = $_SESSION['ds_planningteamcommentcolumn'];
// $ds_ismanagervalidationplanning = $_SESSION['ds_ismanagervalidationplanning'];  
$ds_bankholidayplanningteamvalueid = $_SESSION['ds_bankholidayplanningteamvalueid'];
$ds_numweeksafterendmonthforcheck = $_SESSION['ds_numweeksafterendmonthforcheck'];

#MANAGER ACCESS
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

$PERIOD_DATE = 0;
$PERIOD_WEEK = 1;
$PERIOD_MONTH = 2;
$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$NO_RESULT_SYMBOL = '?';
$STEP_FORM_MODIFYEMPLOYEE = 3;

$STATE_SAVED = 9;
$STATE_SUBMITED = 0;
$STATE_ACCEPTED = 1;
$STATE_REFUSED = 2;

#export file
$CSV_DELIMITER = ';';

#VALUES = COLUMNS FOR EACH DAY (ex: AM/PM/Night + values sup as helmet)
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];
$ds_planningteamnbvaluessup = $_SESSION['ds_planningteamnbvaluessup'];

$ds_termplanningvalueA = array();$ds_termplanningvaluesupA = array();
$isbankholidayA = array(); $isdayoffA = array();

for($v=1;$v<=$ds_planningteamnbvalues;$v++)
{
  $ds_termname = 'ds_term_planningteamvalue' . $v;
  $ds_termplanningvalueA[$v] = $_SESSION[$ds_termname]; 
} 
for($v=1;$v<=$ds_planningteamnbvaluessup;$v++)
{
  $ds_termname = 'ds_term_planningteamvaluesup' . $v;
  $ds_termplanningvaluesupA[$v] = $_SESSION[$ds_termname];   
}

#current date
$ds_curdate = $_SESSION['ds_curdate'];
$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
$currentweek = date(W,$currenttimestamp);
$currentmonth = date(n,$currenttimestamp);
if(startswith($currentweek,'0')){$currentweek = mb_substr($currentweek,1,1);}

#we can come from the planning form or an other page like home page (not yet)
$isplanningteamform = $_POST['isplanningteamform'] + 0;
$isvalidation = $_POST['isvalidation'] +0;
if($isplanningteamform)
{
  #save each parameter in order to post them if validation of planning
  $period_save = $period = $_POST['period'];
  
  switch($period)
  {
    case $PERIOD_DATE:
      $datename = 'date';require('inc/datepickerresult.php');
      $date_save = $date;
      break;
    case $PERIOD_WEEK:
      if ($isvalidation)
      {
        $week_save = $week = $_POST['week'];
        $year_save = $year = $_POST['year'];      
      }
      else
      {
        #week_year by post => must separate week and year
        $week_year = $_POST['week_year'];
        $pos_ = mb_strpos($week_year,'_');  
        $week = mb_substr($week_year,0,$pos_);  
        $year = mb_substr($week_year,$pos_+1);  
        $week_save = $week;
        $year_save = $year;  
      }   
      break;
    case $PERIOD_MONTH:  
      $month_save = $month = $_POST['month'];
      $year_save = $year = $_POST['year'];
      break;
  }

  $employeeid_save = $employeeid = $_POST['employeeid'];
}
else
{
  $period = $PERIOD_WEEK;
  $week = $currentweek;
  $year = $currentyear;
  $employeeid = $ds_myemployeeid;  
}

$ismanager = $_POST['ismanager']+0;
$myemployeedepartmentid = $_POST['myemployeedepartmentid']+0;
$myemployeesectionid = $_POST['myemployeesectionid']+0;
require('hr/chooseemployeewithteams.php');
$dateA[1] = $date;
$onedayoff = 0;
$ismonthperiod= 0;
switch($period)
{
  case $PERIOD_DATE:
    $dateA[1] = $date;
    $nbdays = 1;
  case $PERIOD_WEEK:
    $nbdays = 7; 
    $ourparams .= '<p>' . d_trad('weekparam:',array($week,d_getmonday_todisplay($week,$year),d_getsunday_todisplay($week,$year))) . '</p>';    
    break;
  case $PERIOD_MONTH:
    $month_display = d_trad('month' . $month);
    $nbdays = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $ismonthperiod= 1;    
    $ourparams .= '<p>' . d_trad('monthyearparam',array($month_display,$year)). '</p>';       
    break;
}

$submitform = $_POST['submitform'];
$isclickexport =  0;
if ( $submitform == d_trad('export') ) 
{ 
	$isclickexport =  1;
	$filename = 'hr_planning_dpt' . $myemployeedepartmentid .'_section' . $myemployeesectionid .'_' . date("Y_m_d_H_i_s") . '.csv';
  $filepath = 'customfiles/' . $filename;
  $file = fopen($filepath, "w");
  if (!$file) { echo '<p class=alert>' . d_trad('technicalerrorfilecreation') . '</p>';}
}

for($day=1;$day<=$nbdays;$day++)
{
  if ( $period == $PERIOD_WEEK ) 
  {    
		$date = $dateA[$day] = d_getday($day,$week,$year); 
  }
  else if ( $ismonthperiod )
  {
    $datetime = new DateTime();
    $datetime->setDate($year, $month, $day);
    $date = $dateA[$day] = $datetime->format('Y-m-d');
  }

  #check if there is a bank holiday for this date
  $query = 'select isbankholiday from calendar where date = ? and deleted=0';
  $query_prm = array($date);
  require('inc/doquery.php');
  if ($num_results > 0)
  { 
    $isbankholidayA[$day] = $query_result[0]['isbankholiday']; 
  }
  else
  {
    $isbankholidayA[$day] = 0; 
  }
  
  if(!empty($date))
  {
    $dateday = mb_substr($date,8,2);
    $datemonth = mb_substr($date,5,2);
    $dateyear = mb_substr($date,0,4);
    $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
    $monthA[$day] = date(m,$datetimestamp);
    $monthwithout0A[$day] = date(n,$datetimestamp);
    $yearA[$day] = date(Y,$datetimestamp);
    $dayofweekA[$day] = date(N,$datetimestamp);
    $weektemp = date(W,$datetimestamp);
    if(startswith($weektemp,'0')){$weektemp = mb_substr($weektemp,1,1);} 
    $weekA[$day] = $weektemp;
    $datedayA[$day] = date(j,$datetimestamp);
  } 
  
  #check if it is a day off and if it must be displayed or not (only for week view: for month view: every days are displayed)
  $isdayoffA[$day] = 0;  
  if ( !$ismonthperiod && $ds_planningteamdayoff > 0 && $ds_planningteamdayoff == $dayofweekA[$day] )
  {
    $isdayoffA[$day] = 1;
    $onedayoff = 1;
  }
}

#if there is a formation day get the planningteamvalueid to display it
$query = 'select * from planningteamvalue where istraining=1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results > 0) 
{ 
  $formationplanningteamvalueid = $query_result[0]['planningteamvalueid'];
}

#if there is a day off, get the planningteamvalueid to display it
if ($onedayoff && $ds_planningteamdayoffdisplayed)
{
  $query = 'select * from planningteamvalue where planningteamvaluename like "Repos%"';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) 
  { 
    $dayoffplanningteamid = $query_result[0]['planningteamvalueid'];
    $dayoffplanningteamsymbol = $query_result[0]['planningteamvaluesymbol'];
  }
}
  
if($isplanningteamform)
{
  $title = d_trad('planning');
  if ( $ds_ishrsuperuser || $ismanager )
  {
    $title = d_trad('planningteam');
  }
  showtitle($title);
  echo '<h2>' . $title . '</h2>';
  echo $ourparams;  
}

$ismanagerbutnothimself = 0 ; if ($ds_ishrsuperuser || ($ismanager && ($employeeid != $ds_myemployeeid))) { $ismanagerbutnothimself =  1; }
$ismanagerhimself = 0; if ($ismanager && !$ds_ishrsuperuser && ($employeeid == $ds_myemployeeid)) { $ismanagerhimself = 1; }
$iseverything = $ds_ishrsuperuser || ($ismanager && ($nbemployees > 1 || $ismanagerbutnothimself));
  
if ( $ismonthperiod )
{
  $nbvalues = $ds_planningteamnbvalues;
  $colspan = $nbvalues;  
}
else
{
  $nbvalues = $ds_planningteamnbvalues; 

  if ($iseverything) 
  { 
    $nbvalues += $ds_planningteamnbvaluessup; 
    #+1 for checkbox
    // if($ds_ismanagervalidationplanning == 1) 
		// { 
			$nbvalues += 1; 
		// }
    $colspan = $nbvalues + 1; //+1 for overtime     
  } 
  else
  {
    $colspan = $nbvalues;
  }
}

#validation of this form if this page has been validated
if($isvalidation)
{
  for($e=0;$e<$nbemployees;$e++)
  {
    $employeeid = $employee_todisplayA[$e]['employeeid'];
    $totalovertime_save = $totalovertime = $_POST['totalovertime_' . $employeeid];   
    $onechangeintotalovertime = 0;
    
    for($day=1;$day<=$nbdays;$day++)
    {
      #is this day validated ?
      $validated = $_POST['validate_' . $employeeid . '_' . $day] + 0; 
      $validated_save = $_POST['validate_save' . $employeeid . '_' . $day] + 0;
      
      # values of this day      
      for($v=1;$v<=$ds_planningteamnbvalues;$v++)
      {
        $planningteamvalueidA[$v] = $_POST['planningteamvalueid_' . $employeeid . '_' . $v . '_' . $day];
      }
      for($v=$ds_planningteamnbvalues+1;$v<=3;$v++)
      {
        $planningteamvalueidA[$v] = 0;
      }

      #are the values sup validated? 0 by default
      $onevaluesupmodified = 0;
      for($vs=1;$vs<=$ds_planningteamnbvaluessup;$vs++)
      {
        $valuesup[$vs] = $_POST['valuesup_' . $employeeid . '_' . $vs . '_' . $day] + 0;
        $valuesup_save[$vs] = $_POST['valuesup_save' . $employeeid . '_' . $vs . '_' . $day] + 0;
        if ($valuesup[$vs] !=  $valuesup_save[$vs]) { $onevaluesupmodified = 1; }
      }
      
      for($vs=$ds_planningteamnbvaluessup+1;$vs<=3;$vs++)
      {
        $valuesup[$vs] = 0;
      }

      if (($validated != $validated_save) || $onevaluesupmodified)
      {
        #check if planningteamvalidation already exists. 
        #if yes, get its id
        $query = 'select planningteamvalidationid from planningteamvalidation where employeeid=? and planningdate=?';
        $query_prm = array($employeeid,$dateA[$day]);
        require('inc/doquery.php');
        $planningteamvalidationid = NULL;
        if ($num_results > 0) { $planningteamvalidationid = $query_result[0]['planningteamvalidationid']; }
        
        #if planningteamvalidationid exists: update record unless insert it
        #update instead of replace because if we change nbvalue/nbvaluesup => we don't delete old values
        if ( $planningteamvalidationid > 0)
        {
          $query = 'update planningteamvalidation set validated=?,planningteamvalueid1=?';
          $query_prm = array($validated,$planningteamvalueidA[1]);
          if ( $ds_planningteamnbvalues >=2)
          {
            $query .= ',planningteamvalueid2=?';
            array_push($query_prm,$planningteamvalueidA[2]);
          }
          if ( $ds_planningteamnbvalues >=3)
          {
            $query .= ',planningteamvalueid3=?';
            array_push($query_prm,$planningteamvalueidA[3]);
          } 
          if ( $ds_planningteamnbvaluessup >=1)
          {
            $query .= ',valuesup1=?';
            array_push($query_prm,$valuesup[1]);
          }          
          if ( $ds_planningteamnbvaluessup >=2)
          {
            $query .= ',valuesup2=?';
            array_push($query_prm,$valuesup[2]);
          }
          if ( $ds_planningteamnbvaluessup >=3)
          {
            $query .= ',valuesup3=?';
            array_push($query_prm,$valuesup[3]);
          } 
          $query .= ' where planningteamvalidationid=?';
          array_push($query_prm,$planningteamvalidationid);

        }
        else
        {
          $query = 'insert into planningteamvalidation(employeeid,planningdate,validated,planningteamvalueid1,planningteamvalueid2,planningteamvalueid3,valuesup1,valuesup2,valuesup3) values (?,?,?,?,?,?,?,?,?)';
          $query_prm = array($employeeid,$dateA[$day],$validated,$planningteamvalueidA[1],$planningteamvalueidA[2],$planningteamvalueidA[3],$valuesup[1],$valuesup[2],$valuesup[3]);
        }
        require('inc/doquery.php');
      }
      
      #save overtime
      $overtime = $_POST['overtime_' . $employeeid . '_' . $day];   
      $overtime_save = $_POST['overtimesave_' . $employeeid . '_' . $day];

      #for days off not displayed
      if ($overtime == '') { $overtime = '+00:00';}
    
      #check if overtime entered by user is correct
      if($ismanagerbutnothimself)
      {
        if (d_checkovertime($overtime)) 
        { 
          $overtime = d_overtimetominutes($overtime);
        }
        else
        {
          echo '<p class=alert>' . d_trad('checkovertimeparam',array($overtime,$employeesortedbyteamA[$employeeid],$dateA[$day])) . '</p>';        
          $overtime = $overtime_save; 
        }
      
        if ($overtime_save != $overtime) 
        {
          #subtract old overtime to total and add new overtime 
          $totalovertime = $totalovertime - $overtime_save + $overtime;   
          
          #check if record already exists and get id if exists
          $query = 'select overtimeid from overtime where employeeid=? and date=?';
          $query_prm = array($employeeid,$dateA[$day]);
          require('inc/doquery.php');
          $overtimeid = NULL;
          if ($num_results > 0) { $overtimeid = $query_result[0]['overtimeid']; } 
          $query = 'replace into overtime(overtimeid,employeeid,overtime,date) values (?,?,?,?)';
          $query_prm = array($overtimeid,$employeeid,$overtime,$dateA[$day]);       
          require('inc/doquery.php');
        }
      }
    }
    
    #save totalovertime for sunday for this employee
    #check if record already exists and get id if exists
    $query = 'select totalovertimeid from totalovertime where employeeid=? and week=? and year=?';
    $query_prm = array($employeeid,$week,$year);
    require('inc/doquery.php');
    $totalovertimeid = NULL;
    if ($num_results > 0) { $totalovertimeid = $query_result[0]['totalovertimeid']; } 
    $query = 'replace into totalovertime(totalovertimeid,employeeid,totalovertime,week,year) values (?,?,?,?,?)';
    $query_prm = array($totalovertimeid,$employeeid,$totalovertime,$week,$year);
    require('inc/doquery.php');
      
    #if there is one change in totalovertime => we have to spread it to further weeks      
    if ($totalovertime != $totalovertime_save)
    {
      #check if records already exist and get id if exist
      $query = 'select * from totalovertime where employeeid=? and ((week>? and year = ? ) or year >? )';
      $query_prm = array($employeeid,$week,$year,$year);
      require('inc/doquery.php');
      $row = $query_result;$numtotal = $num_results; unset($query_result,$num_results);
      $diff = $totalovertime - $totalovertime_save;
      for ($t=0;$t<$numtotal;$t++) 
      { 
        $totalovertimeid = $row[$t]['totalovertimeid']; 
        $totalovertime_tochange = $row[$t]['totalovertime'];
        $totalovertime_tochange += $diff;
        $totalovertimeweek = $row[$t]['week'];        
        $totalovertimeyear= $row[$t]['year'];        
        $query = 'replace into totalovertime(totalovertimeid,employeeid,totalovertime,week,year) values (?,?,?,?,?)';
        $query_prm = array($totalovertimeid,$employeeid,$totalovertime_tochange,$totalovertimeweek,$totalovertimeyear);
        require('inc/doquery.php');
      }
    }
    
    #comment
    if ( $ds_planningteamcommentcolumn == 1)
    {
      $comment = $_POST['comment_' . $employeeid . '_' . $week . '_' . $year];
      $comment_save = $_POST['commentsave_' . $employeeid . '_' . $week . '_' . $year];
      
      if($comment != $comment_save)
      {
        #check if records already exist and get id if exist
        $query = 'select * from planningteamcomment where employeeid=? and week=? and year=?';
        $query_prm = array($employeeid,$week,$year);
        require('inc/doquery.php');
        $planningteamcommentid = NULL;
        if ( $num_results > 0 ) { $planningteamcommentid = $query_result[0]['planningteamcommentid']; }
        $query = 'replace into planningteamcomment(planningteamcommentid,employeeid,comment,week,year) values (?,?,?,?,?)';
        $query_prm = array($planningteamcommentid,$employeeid,$comment,$week,$year);
        require('inc/doquery.php');
      }
    }
  }//for employee
}//validation

#don't deplace it
session_write_close();

#to create xls file
$xlsA = array();

#display table title 
if(!$isplanningteamform)
{
  echo '<div class="myblock" style="width:90%;margin:auto;">';
}

echo '<form method="post" action="reportwindow.php" class="planning">';
echo '<table class="formplanning"><tbody><tr><td>';
echo '<table class=planning>';
echo '<thead>';

if($nbemployees > 1)
{
  $isseparation = 0;
  #for employee name column
  echo '<th class=planningtitle></th>';array_push($xlsA,'');
}

#First line  of title: date
for($day=1;$day<=$nbdays;$day++)
{
  $isdayoff = $isdayoffA[$day];  
  if ( !($isdayoff && !$ds_planningteamdayoffdisplayed))  
  {
    if (($day == 1 && $nbemployees > 1) || $day > 1 )
    {
      #one column to separate days
      echo '<th class=planningseparation>&nbsp;</th>'; 
    }
    
    if ($ismonthperiod)
    {
			$daydisplayed = d_trad('initialdayofweek'.$dayofweekA[$day]) .$day;
      echo '<th class=planningtitlemonth colspan="' . $colspan . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $daydisplayed . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
			#export file
			array_push($xlsA,$daydisplayed); for ($col=0;$col < $colspan -1;$col++){ array_push($xlsA,'');  }	
    }
    else
    {
			$daydisplayed = d_trad('datewithwords' , array(d_trad('dayofweek'.$dayofweekA[$day]),$datedayA[$day],d_trad('month'.$monthA[$day])));
      echo '<th class=planningtitle colspan="' . $colspan . '">' . $daydisplayed . '</th>'; 
			array_push($xlsA,$daydisplayed); for ($col=0;$col < $colspan -1;$col++){ array_push($xlsA,'');  }	
    }
  }
}
  
#totalovertime column
if ($iseverything )  
{
  #one column to separate days
  echo '<th class=planningseparation>&nbsp;</th>'; 

  echo '<th class=planningtitle>' .  d_trad('totalovertime') . '</th>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalovertime'))); 
}
#comment column
if ( $ds_planningteamcommentcolumn && $iseverything)
{
  echo '<th class=planningtitle>' .  d_trad('comment') . '</th>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('comment'))); 
}
echo '</thead>';
$nbdaysdisplayed = $nbdays;
#export file
if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA, $CSV_DELIMITER ); }

#Second line of title : tem_planningteamvalue1....to 3 depends on nbvalues
if (!$ismonthperiod)
{
	#for a new line in export file
  $xlsA = array();
	
  echo '<thead>';
  if($nbemployees > 1)
  {
    #for employee name column
    echo '<th class=planningtitle></th>';array_push($xlsA,'');
  }
  for($day=1;$day<=$nbdays;$day++)
  {
    $isdayoff = $isdayoffA[$day];    
    if ( ($isdayoff && !$ds_planningteamdayoffdisplayed) )  
    {
      $nbdaysdisplayed --;
    }
    elseif ( !($isdayoff && !$ds_planningteamdayoffdisplayed) )  
    {
      if (($day == 1 && $nbemployees > 1) || $day > 1)
      {      
        #one column to separate days
        echo '<th class=planningseparation>&nbsp;</th>';
      }
    
      if ($iseverything == 1) #&& (($ds_ismanagervalidationplanning == 1)
      {
        #one column for checkbox
        echo '<th class=planningtitle></th>';array_push($xlsA,'');
      }
    
      for($v=1;$v<=$ds_planningteamnbvalues;$v++)
      {
        echo '<th class=planningtitle>' . $ds_termplanningvalueA[$v] . '</th>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$ds_termplanningvalueA[$v]));
      }
      if ($iseverything == 1)
      { 
        for($v=1;$v<=$ds_planningteamnbvaluessup;$v++)
        {
          echo '<th class=planningtitle>' . $ds_termplanningvaluesupA[$v] . '</th>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$ds_termplanningvaluesupA[$v]));
        }
     
        #overtime column      
        echo '<th class=planningtitle>' .  d_trad('overtime') . '</th>';  array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('overtime')));
      }
    }
  }
  
  if ($iseverything == 1)
  {   
    #one column to separate days
    echo '<th class=planningseparation>&nbsp;</th>';
    
    #totalovertime column
    echo '<th class=planningtitle></th>';array_push($xlsA,'');
  }
  
  #comment column
  if ( $ds_planningteamcommentcolumn && ($iseverything == 1))
  {
    echo '<th class=planningtitle></th>';array_push($xlsA,'');
  }
  echo '</thead><tbody class=planningbody>';
	#export file
	if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }	
}
  
# DISPLAY RESULTS
#for each employee = each line
for($e=0;$e<$nbemployees;$e++)
{
  $employeeid = $employee_todisplayA[$e]['employeeid'];
  $ismanagerbutnothimself = 0 ; if ($ds_ishrsuperuser || ($ismanager && ($employeeid != $ds_myemployeeid))) { $ismanagerbutnothimself =  1; }
  $ismanagerhimself = 0; if ($ismanager && !$ds_ishrsuperuser && ($employeeid == $ds_myemployeeid)) { $ismanagerhimself = 1; }
  $iseverything = $ds_ishrsuperuser || ($ismanager && ($nbemployees > 1 || $ismanagerbutnothimself));  
  $employeename = $employeesortedbyteamA[$employeeid];
 
  #disable if employee is not manager or super user or if he is manager himself
  $disabled_manager = '';

  if ($ismanagerhimself || (!$ds_ishrsuperuser && !$ismanager))
  {
    $disabled_manager = ' disabled = "disabled"';
  }
 
  $planningemployeeclass = 'planningemployeenotmanager';
  $planningclass = 'planningnotmanager';

  if ($employeesortedbyteam_ismanagerA[$employeeid] == 1)
  {
    $planningemployeeclass = 'planningemployeemanager';
    $planningclass = 'planningmanager';
  }

	#for a new line in export file
  $xlsA = array();
  
  echo d_tr();
  if($nbemployees > 1)
  {      
    echo '<td class=' . $planningemployeeclass . '>';
    if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $employeeid . '" target=_blank>' .  d_output($employeename) . '</a>';}
    else { echo d_output($employeename); } 
    echo '</td>'; 
		array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_output($employeename))); 
  }
  #each day = each group of columns
  $totalovertime = 0;
  $comment = '';
  $weekchange = 0; 

	$errordisplayed = 0;
  for($day=1;$day<=$nbdays;$day++)
  {   
    $isdayoff = $isdayoffA[$day];  
    $istobedisplayed = 1;    
    #for month period: display every day even if there are off
    if ( !$ismonthperiod && $isdayoff && !$ds_planningteamdayoffdisplayed)
    {
      $istobedisplayed = 0;
    }
    if ($istobedisplayed)
    { 
      $date = $dateA[$day];
      $dayofweek = $dayofweekA[$day];
      $dateday = $datedayA[$day];
      $month = $monthwithout0A[$day];
      $year = $yearA[$day];
      
      #update disable: if it planningdate to be displayed 
			#is before begining of lastmonth 
			#is last month but currentdate is after last date to check last month
			#(beginning of current month+ nb weeks set in hroptions (ds_numweeksafterendmonthforcheck))
      $disabled = $disabled_manager;
      if (!$ds_ishrsuperuser)
      {
				$currentdate = DateTime::createFromFormat('Y-m-d', $ds_curdate);
				// to test $currentdate = DateTime::createFromFormat('Y-m-d', '2016-02-09');
				$datetime = DateTime::createFromFormat('Y-m-d', $date);	#planning date to be displayed
				$firstdaylastmonthdate = d_getfirstdayoflastmonthdate($currentmonth,$currentyear);
				$firstdaymonthdate = d_getfirstdayofmonthdatetime($currentmonth,$currentyear);
				$datemaxcheck = d_getdateaddweeks($ds_numweeksafterendmonthforcheck,$currentmonth,$currentyear);
				$datetimemaxcheck = DateTime::createFromFormat('Y-m-d', $datemaxcheck);	
				
				if ($datetime <= $currentdate )
				{
					if($datetime < $firstdaylastmonthdate)
					{
						$disabled = 'disabled = "disabled"';					
					}
					elseif (($datetime < $firstdaymonthdate) && ($currentdate > $datetimemaxcheck))
					{
						$disabled = 'disabled = "disabled"'; 					
					}
				}
				else
				{
					$disabled = 'disabled = "disabled"'; 		
				}
      }

      $week = $weekA[$day];
      if ($day == 1) { $weekprev = $week; $weekchange = 1;}
      elseif ($week != $weekprev) { $weekchange = 1;} else {$weekchange = 0;}
   
      $isbankholiday = $isbankholidayA[$day];
         
      #check if there is a formation for this employee
      $istrainingday = 0;      
      $query = 'select tep.trainingemployeeplanningid from trainingemployeeplanning tep,trainingplanning tp where tep.trainingplanningid = tp.trainingplanningid and tep.employeeid=? and tep.deleted=0 and tp.deleted=0 and tp.startdate<=? and tp.stopdate>=?';
      $query_prm = array($employeeid,$dateA[$day],$dateA[$day]);
      require('inc/doquery.php');
      if ($num_results > 0) { $istrainingday = 1;}
      
			$isapunctualrecord = 0;
      if ($istobedisplayed)
      {
        #1- record only for this date like attendance/absence or yearly
        $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ') and ';
        $query .= '(( pt.periodic = 0 and pt.planningdate=? ) or (pt.periodic = 3 and DAY(pt.planningdate) = ? and MONTH(pt.planningdate) = ?))';
        $query_prm = array($employeeid,$date,$dateday,$month);
        require('inc/doquery.php');
        if($num_results > 0)
        {
          $row = $query_result[0];
					if ($isdayoff) { $isapunctualrecord = 1;}
        }
        else
        {   
          #2- record for a period like vacations/ sick leave
          $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ') and (pt.planningstart <= ? and pt.planningstop >= ?)';   
          $query_prm = array($employeeid,$date,$date);  
          require('inc/doquery.php');
          if($num_results > 0)
          {
            $row = $query_result[0];
          }
          else
          {   
            #3- record for a month period: will take the record with biggest planning_spec id to display
            $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ') and ';
            $query_prm = array($employeeid);  
            $query .= ' (pt.periodic = 2 and DAY(pt.planningdate) = ?';array_push($query_prm,$dateday);     
              #every month
              $query .= ' and (';
                $query .= '(pt.periodic_spec=0)'; 
                if($month%2 == 1)
                {
                  #every odd month
                  $query .= ' or (pt.periodic_spec=1)';
                }
                else
                {
                  #every even month 
                  $query .= ' or (pt.periodic_spec=2)';
                }
                #every 3 months
                $query .= ' or (pt.periodic_spec=3 and ? in (MONTH(pt.planningstart) -9, MONTH(pt.planningstart) -6,MONTH(pt.planningstart) -3,MONTH(pt.planningstart),MONTH(pt.planningstart) + 3, MONTH(pt.planningstart) +6 , MONTH(pt.planningstart) + 9))';array_push($query_prm,$month);   
                #every 6 months
                $query .= ' or (pt.periodic_spec=4 and ? in (MONTH(pt.planningstart) -6, MONTH(pt.planningstart), MONTH(pt.planningstart) +6 ))';array_push($query_prm,$month);   
              $query .= ')';
            $query .= ') order by pt.periodic_spec desc';

            require('inc/doquery.php');
            if($num_results > 0)
            {
              $row = $query_result[0];
            }
            else
            {   
              #4- record for a week: : will take the record with biggest planning_spec id to display
              $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ') and ';
              $query_prm = array($employeeid); 
              $query .= ' (pt.periodic = 1 and pt.dayofweek = ?';array_push($query_prm,$dayofweek);  
                #every week
                $query .= ' and (';
                  $query .= '(pt.periodic_spec=0)'; 
                  if($week%2 == 1)
                  {
                    #every odd week 
                    $query .= ' or (pt.periodic_spec=1)';
                  }
                  else
                  {
                    #every even week 
                    $query .= ' or (pt.periodic_spec=2)';
                  }
                  if($dateday >=1 && $dateday <=7)
                  {
                    #every 1st week of month
                    $query .= ' or (pt.periodic_spec=3)';
                  }
                  else if($dateday >=8 && $dateday <=14)
                  {
                    #every 2nd week of month
                    $query .= ' or (pt.periodic_spec=4)';
                  }
                  else if($dateday >=15 && $dateday <=21)
                  {     
                    #every 3rd week of month
                    $query .= ' or (pt.periodic_spec=5)';
                  }
                  else if($dateday >=22 && $dateday <=31)
                  {  
                    #every 4th week of month
                    $query .= ' or (pt.periodic_spec=6)';  
                  }
                $query .= ')';
              $query .= ') order by pt.periodic_spec desc';

              require('inc/doquery.php');
              if($num_results > 0)
              {
                $row = $query_result[0];       
              }
            }//if #3 no result
          }//if #2 no result
        }//if #1 no result
        unset($query,$query_prm,$num_results,$query_result);
      }
			if (!isset($row) && $errordisplayed == 0) 
			{ 	
				if ($ds_systemaccess)
				{
					echo '<p class= alert>' . d_trad('pleasedefineschedule',d_output($employeename)) .'<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $employeeid . '" target=_blank> ici.</a></p>';
				}
				else
				{
					echo '<p class= alert>' . d_trad('pleasedefineschedulenosystemaccess',d_output($employeename)) . '</p>';
				}
				$errordisplayed = 1;
			}
      
      if ( ($day == 1 && $nbemployees > 1) || ($day > 1) )
      {
        #one column for separation
        echo '<td class=planningseparation>&nbsp;</td>';
      }
      
      #get validated values
      #one column to validate or to show it is already validated
      $query = 'select * from planningteamvalidation where employeeid= ? and planningdate=?';
      $query_prm = array($employeeid,$date);
      require('inc/doquery.php');
      $validated = -1;
      $validatedvalues1 = 0;  
      $colspanday = 1;
      
      if ($num_results > 0)
      {
        $validated = $query_result[0]['validated'] + 0;

        // for($vv=1;$vv<=$ds_planningteamnbvalues;$vv++)
        // {
          // #if there is a validated value, display it instead of planed value      
          // $validatedvalue = $row['planningteamvalueid' . $vv] = $query_result[0]['planningteamvalueid'.$vv];  
         
          // /*if( $vv == 1 )
          // {
            // $validatedvalues1 = $query_result[0]['planningteamvalueid'.$vv];
          // }
          // elseif ( ($vv > 1) && ($validatedvalue == $validatedvalues1) )
          // {
            // $colspanday ++;
          // }*/
        // }

        /*#colspan only if all the values are equals
        if ( $colspanday < $ds_planningteamnbvalues )
        {
          $colspanday = 1;
        }*/
      }
      /*else
      {
        #if no values : display ? with colspan
        $colspanday = $ds_planningteamnbvalues;
      }*/
      if ( $ds_planningteamcommentcolumn && ($iseverything == 1) && ( ($ismonthperiod && $weekchange) || (!$ismonthperiod && ($day == $nbdaysdisplayed) )))
      {  
        #get comments
        $query = 'select * from planningteamcomment where employeeid= ? and week=? and year=?';
        $query_prm = array($employeeid,$week,$year);
        require('inc/doquery.php');
        $commentA = $query_result; $num_comments = $num_results;
        
        if($num_comments > 0)
        {        
          if ($ismonthperiod)
          {
            #concat comments
            $comment .=  $commentA[0]['comment'] . '<br>';
          }
          else
          {
            #take the last one for week period
            $comment = $commentA[0]['comment'];
          }
        }
      }
      
      #validation of planning for managers and superuser
      $checked = ''; $checked_display = '';
      if ( !$ismonthperiod && ($iseverything == 1)) # && ($ds_ismanagervalidationplanning == 1))
      {    
        if ($validated == 1)
        {
          $checked = 'CHECKED';
          $checked_display = 'CHECKED';
        }
        // elseif ($isbankholiday)
        // {
          // $checked_display = 'CHECKED';        
        // }      
				// else if ($isdayoff && $ds_planningteamdayoffdisplayed && $isapunctualrecord == 0 && $validated != 0)
        // {
					// #if it is a off day, verify that it has not been changed
          // $checked_display = 'CHECKED';        
        // }      				

				#update disable for absence/presence juste submited not accepted yet => can not be validated
        if(isset($row) && !$isbankholiday && !$istrainingday)
        {  
          $planningteamstate = $row['state'];     
          $periodic = $row['periodic']; 
					if ($planningteamstate == 0 && $periodic == 0) { $disabled = 'disabled = "disabled"';}
				}
        echo '<td class=planningempty><input type=checkbox value=1 name="validate_' . $employeeid . '_' . $dayofweek . '" ' . $disabled . ' ' . $checked_display . '/></td>';
        $value_checked = 0; if($checked == 'CHECKED') { $value_checked = 1; array_push($xlsA,'V');} else { array_push($xlsA,'');}
        echo '<input type=hidden name="validate_save' . $employeeid . '_' . $dayofweek . '" value=' . $value_checked . '>';
      }
			
			#keep this line because we need to know if it is un-validated for days off (l.1017)
			if ($validated == -1) { $validated = 0;}
      
      #each column for each day
      for($v=1;$v<=$ds_planningteamnbvalues;$v++)
      {   
        if(!isset($row) && !$isbankholiday && !$istrainingday)
        {
          #no result
          //if ( ($colspanday == 1) || ($colspanday > 1 && $v == 1) )
          //{
            echo '<td class=planningnoresult colspan=' . $colspanday . '>' . $NO_RESULT_SYMBOL .'</td>';
						#export file
						array_push($xlsA,$NO_RESULT_SYMBOL);
						for ($col=0;$col < $colspanday -1;$col++){ array_push($xlsA,'');  }	
          //}
        }
        else
        {   
          $planningteamid = $row['planningteamid'];
          $planningteamvalueid = $row['planningteamvalueid' . $v];
          $planningteamstate = $row['state'];     
          $periodic = $row['periodic'];  					
          
          #only if nothing punctual in planning except bankholiday/formation
          #we display it 
          if ($periodic == 0)
          {
            $isbankholiday = $istrainingday = 0;
          }
          else
          {
            if ($isbankholiday )
            { 
              $planningteamvalueid = $ds_bankholidayplanningteamvalueid; 
              $periodic = 0;
              $planningteamstate = 1;
            }
            else if($istrainingday)
            {
              $planningteamvalueid = $formationplanningteamvalueid;
              $periodic = 0;
              $planningteamstate = 1;          
            }
          }
        
          if ( $planningteamvalueid != NULL )
          {
            $planningteamvaluesymbol = $planningteamvalue_symbolA[$planningteamvalueid];           
            $planningteamvaluecolorid = $planningteamvalue_coloridA[$planningteamvalueid];
          }
          else
          {
            $planningteamvaluesymbol = $NO_RESULT_SYMBOL;   
          }
          
          echo '<td class=' .$planningclass .' style="background-color: #' . $color_codeA[$planningteamvaluecolorid] . ';';          
          if(($planningteamstate  == 0) && ($periodic == 0 ))
          { 
            echo 'font-style: italic;color: #080808;'; 
          }
          echo '">';
                
          if ($iseverything == 1)
          {
            if ($disabled == '' && $checked == '' && !$validated) 
            { 
              if ($isbankholiday == 0)
              {
                echo '<a href="hr.php?hrmenu=addabsencepresence&modid=' .$planningteamid . '&date=' . $date . '" target=_blank';              
              }
              else
              {
                ##for bank holiday we put bank holiday planningteamvalueid instead of planningteamid with a "-" and we pass employeeid 
                ## because no record in planningteam yet
                echo '<a href="hr.php?hrmenu=addabsencepresence&modid=-' .$ds_bankholidayplanningteamvalueid . '&date=' . $date . '&eid=' . $employeeid . '" target=_blank';
              }
              echo ' style="background-color: #' . $color_codeA[$planningteamvaluecolorid] . ';';          
              if(($planningteamstate  == 0) && ($periodic == 0 ))
              { 
                echo 'font-style: italic;color: #080808;'; 
              }
              echo '">' . $planningteamvaluesymbol . '</a>'; 
           }
            else { echo $planningteamvaluesymbol;}
          }
          else { echo $planningteamvaluesymbol;} 
        }
				array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$planningteamvaluesymbol));
        
        echo '<input type=hidden name="planningteamvalueid_' . $employeeid . '_' . $v . '_' . $dayofweek . '" value="' . $planningteamvalueid .'"/> ';       
        echo '</td>';    
      }
   
      #value sup
      if(!$ismonthperiod && ($iseverything == 1))
      {
        for($vs=1;$vs<=$ds_planningteamnbvaluessup;$vs++)
        {
          $checkedsup = '';

          if($num_results > 0)
          {
            $valuesup = $query_result[0]['valuesup' . $vs] + 0;
            if ($valuesup == 1)
            {
              $checkedsup = 'CHECKED';
            }
          }
          echo '<td class=' .$planningclass .'><input type=checkbox value=1 name="valuesup_' . $employeeid . '_' . $vs . '_' . $dayofweek . '" ' . $disabled . ' ' . $checkedsup . '/></td>';
          $value_checkedsup = 0; if($checkedsup == 'CHECKED') { $value_checkedsup = 1; array_push($xlsA,'V');} else { array_push($xlsA,'');}
          echo '<input type=hidden name="valuesup_save' . $employeeid . '_' . $vs . '_' . $dayofweek .'" value=' . $value_checkedsup . '>';      
        }
      }
    }

    if (!$ismonthperiod && $iseverything && $istobedisplayed)
    {
      #overtime
      $query = 'select overtime from overtime where employeeid=? and date=?';
      $query_prm = array($employeeid,$date);
      require('inc/doquery.php');
      $overtime_save = 0;
      if ($num_results > 0) 
      { 
        $overtime_save = $query_result[0]['overtime']; 
      }
    }
          
    if (!$ismonthperiod && $iseverything && $istobedisplayed)
    {    
      #overtime
      echo '<td class=' .$planningclass . '><input type=text name="overtime_' . $employeeid . '_' . $dayofweek . '" value="' . d_displayovertime($overtime_save) . '" size=6></td>';array_push($xlsA,d_displayovertimecsv($overtime_save));
      echo '<input type=hidden name="overtimesave_' . $employeeid . '_' . $dayofweek . '" value="' . $overtime_save .'">';    
    }
    unset($row);
    $weekprev = $week;
  }//for $iday
  
  #total overtime
  if ($iseverything)  
  {
    if ($ismonthperiod)
    {
      $daystop = d_getfirstdayofmonthdate($month+1,$year);
      $weekstop = date(W,$daystop);  
      if(startswith($weekstop,'0')){$weekstop = mb_substr($weekstop,1,1);}
      $yearstop = date(Y,$daystop);
      $query = 'select totalovertime from totalovertime where employeeid=? and ((week<? and year=?) or year<? ) order by year desc,week desc limit 1';
      $query_prm = array($employeeid,$weekstop,$yearstop,$yearstop);
    }
    else
    {
      #take week totalovertime
      $query = 'select totalovertime from totalovertime where employeeid=? and ((week<=? and year=?) or year<? ) order by year desc,week desc limit 1';
      $query_prm = array($employeeid,$weekA[$nbdays],$yearA[$nbdays],$yearA[$nbdays]);
    }
    require('inc/doquery.php');
    $totalovertime = 0;
    if ($num_results > 0) 
    { 
      $totalovertime = $query_result[0]['totalovertime'];
    }
    
    #one column to separate days
    echo '<td class=planningseparation>&nbsp;</td>'; 
  
    echo '<td class=' .$planningclass . '>' . d_displayovertime($totalovertime) . '</td>'; array_push($xlsA,d_displayovertimecsv($totalovertime));
    echo '<input type=hidden name="totalovertime_' . $employeeid . '" value="' . $totalovertime . '"</td>';
  }
  
  #comment  
  if ( $ds_planningteamcommentcolumn && ($iseverything == 1) )
  {       
    if ($ismonthperiod)
    {
      echo '<td class=' .$planningclass . ' size=30 >' . $comment . '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$comment));
    }
    else
    {
      echo '<td class=' .$planningclass . '><input type=text name="comment_' . $employeeid . '_' . $week . '_' . $year . '" value="' . $comment . '" size=30 ' . $disabled_comment .'></td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$comment));
    }
    echo '<input type=hidden name="commentsave_' . $employeeid . '_' . $week . '_' . $year . '" value="' . $comment .'">';    
  }
  else if($iseverything == 1)
  {
    echo '<td class=' .$planningclass . '></td>';array_push($xlsA,'');
  }
  echo '</tr>';  
	if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
}//for $employeeid

echo '</tbody></table></td></tr>';
if (!$ismonthperiod )
{
  if ($nbemployees > 1)
  {
    #one column for employee name  and one column for separation for each day
    $colspan = (($nbvalues +1)* 7) +1;
  }
  else
  {
    #one column for separation for each day unless for monday
    $colspan = (($nbvalues +1)* 7) -1;
  }
  if ($iseverything == 1)
  {
    #one column for overtime for each day + total overtime
    $colspan += 7 + 1;
  }

  #validation of planning
  if ($iseverything == 1) #&& $ds_ismanagervalidationplanning == 1)
  {
    echo '<tr><td colspan=' . $colspan  . '><input type=checkbox onClick="toggle(this);">' . d_trad('toggleall') . '</td></tr>';  
    echo '<tr><td colspan=' . $colspan  . ' align=center>';
		if ($isclickexport == 0)
		{
			echo '<input type="submit" name="submitform" value="' . d_trad('export') . '">';
		}
		else
		{
			echo '<button><a href="' . $filepath .'" download="' .$filename .'" class="btn btn-success">' . d_trad('download') . '</a></button>';
		}
		echo '<input type="submit" name="submitform" value="' . d_trad('validate') . '"></td>';
  }
  echo '<input type=hidden name="hrmenu" value="hr_planningteam">';
  echo '<input type=hidden name="report" value="hr_planningteam">';
  echo '<input type=hidden name="isplanningteamform" value="1">';  
  echo '<input type=hidden name="isvalidation" value="1">'; 
  #in order to display again resulttable
  echo '<input type=hidden name="period" value="' . $period_save . '">'; 
  echo '<input type=hidden name="date" value="' . $date_save. '">';
  echo '<input type=hidden name="week" value="' . $week_save .'">'; 
  echo '<input type=hidden name="month" value="' . $month_save .'">'; 
  echo '<input type=hidden name="year" value="' . $year_save .'">'; 
  echo '<input type=hidden name="employeeid" value="' . $employeeid_save .'">';   
  echo '<input type=hidden name="ismanager" value="' . $ismanager .'">';   
  echo '<input type=hidden name="myemployeedepartmentid" value="' . $myemployeedepartmentid .'">';   
  echo '<input type=hidden name="myemployeesectionid" value="' . $myemployeesectionid .'">';   

  echo '</tr>';
  echo '</tbody></table></form>';
}
  
if(!$isplanningteamform)
{
  echo '</div><br>';
}

?>