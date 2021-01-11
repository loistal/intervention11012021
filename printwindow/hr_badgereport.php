<?php
### keep this
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
if ($ds_systemaccess != 1) { require('logout.php'); exit; }
require ('inc/standard.php');
$dauphin_currentmenu = basename(__FILE__, '.php');?>

<link rel="stylesheet" href="printwindow/hr_report.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<link rel="stylesheet" href="declaration/print.css">

<?php
require('inc/func_planning.php');
if (!isset($employeesortedbyteamA)) {require ('preload/employeesortedbyteam.php');}
unset($planningteamvalueA);$hr_orderby_absence = 1;require ('preload/planningteamvalue.php');
if(!isset($colorA)){require ('preload/color.php');}


$PERIOD_WEEK = 1;
$PERIOD_MONTH = 2;
$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$STEP_FORM_MODIFYEMPLOYEE = 3;
#TODO parameters
$NIGHT_START_TIME = '20:00:00';
$NIGHT_STOP_TIME = '06:00:00';
$OVERTIME_FIRST_START_HOUR = 39;
$OVERTIME_FIRST_START_HOUR_MINUTES = 39 * 60;
$OVERTIME_SECOND_START_HOUR = 47;
$OVERTIME_SECOND_START_HOUR_MINUTES = 47 * 60;

#export file
$CSV_DELIMITER = ';';


#HR Variables
$ds_planningteamdayoff = $_SESSION['ds_planningteamdayoff'];
$ds_planningteamdayoffdisplayed = $_SESSION['ds_planningteamdayoffdisplayed'];
$ds_planningteamcommentcolumn = $_SESSION['ds_planningteamcommentcolumn'];
// $ds_ismanagervalidationplanning = $_SESSION['ds_ismanagervalidationplanning'];
$ds_lunchtime = $_SESSION['ds_lunchtime'];

#MANAGER ACCESS
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

#don't deplace it
session_write_close();

#get parameters
#if page reloaded by click son export
$isclickexport = $_GET['isclick'] +0;
if ($isclickexport == 1) 
{
  $period = $_GET['period'];
  $week_year = $_GET['week_year'];
  $month = $_GET['mon'];
  $year_save = $year = $_GET['year'];
  $employeeid_save = $_GET['empid'];
  $employeeid = $employeeid_save;
  $ismanager = $_GET['ism']+0;
  $myemployeedepartmentid = $_GET['mydid']+0;
  $myemployeesectionid = $_GET['mysid']+0;

  $filename = 'hr_badgereport_dpt' . $myemployeedepartmentid .'_section' . $myemployeesectionid .'_' . date("Y_m_d_H_i_s") . '.csv';
  $filepath = 'customfiles/' . $filename;

  $file = fopen($filepath, "w");
  if (!$file) { echo '<p class=alert>' . d_trad('technicalerrorfilecreation') . '</p>';}
}
else
{
  $period = $_POST['period'];
  $week_year = $_POST['week_year']; 
  $month = $_POST['month'];
  $year_save = $year = $_POST['year'];
  $employeeid = $employeeid_save = $_POST['employeeid'];
  $ismanager = $_POST['ismanager']+0;
  $myemployeedepartmentid = $_POST['myemployeedepartmentid'] +0;
  $myemployeesectionid = $_POST['myemployeesectionid']+0;  
}

switch($period)
{
  case $PERIOD_WEEK:
    #week_year by post => must separate week and year
    $pos_ = mb_strpos($week_year,'_');  
    $week = mb_substr($week_year,0,$pos_);  
    $year = mb_substr($week_year,$pos_+1);  
    $week_save = $week;     
    break;
  case $PERIOD_MONTH:  
    $month_save = $_POST['month'];
    break;
}

require('hr/chooseemployeewithteams.php');

#display params
$employeeidempty = 1;

$nbdays = 0;
switch($period)
{
  case $PERIOD_WEEK:
    #to get totalovertime of previous week: need to calculate previous week
    if ($week == 1)
    {
      $prevyear = $year -1;    
      $prevweek = d_getnbweeksinyear($prevyear);;
    }
    else
    {
      $prevweek = $week -1;
      $prevyear = $year;
    }
    #this calculation handle year change
    $reportstart = d_getmonday($week,$year);
    $reportstop = d_getmonday($week+1,$year);  
    $ourparams2 = '<p>' . d_trad('weekparam:',array($week,d_getmonday_todisplay($week,$year),d_getsunday_todisplay($week,$year))). '</p>';   
    
    $nbdays = 7;
    $nbdaysoff = 0;
    if ($ds_planningteamdayoff > 0)
    {
      $nbdaysoff = 1;
    }   
    
    break;
  case $PERIOD_MONTH:
    $month_display = d_trad('month' . $month);
    #this calculation handle year change    
    $reportstart = d_getfirstdayofmonth($month,$year);
    $reportstop = d_getfirstdayofmonth($month+1,$year);   
    $ourparams2 = '<p>' . d_trad('monthyearparam',array($month_display,$year)). '</p>';   

    $nbdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $daystart = d_getfirstdayofmonthdate($month,$year);
    $weekstart = date(W,$daystart);  
    if(startswith($weekstart,'0')){$weekstart = mb_substr($weekstart,1,1);}
    $monthstart = date(n,$daystart);
    $yearstart = date(Y,$daystart);  
    $daystop = d_getfirstdayofmonthdate($month+1,$year);
    $weekstop = date(W,$daystop);  
    if(startswith($weekstop,'0')){$weekstop = mb_substr($weekstop,1,1);}
    $monthstop = date(n,$daystop);
    $yearstop = date(Y,$daystop);

      
    #calculate how many days off in this month
    $nbdaysoff = 0;
    if ($ds_planningteamdayoff > 0)
    {
      $day = new DateTime();        
      for($d=1;$d<=$nbdays;$d++)
      {
        $datetimestamp = mktime(0,0,0,$month,$d,$year);
        $dayofweek = date(N,$datetimestamp);
        if ( $ds_planningteamdayoff == $dayofweek )
        {
          $nbdaysoff ++;
        }
      }
    }    
    break;
}

#check if there are bank holidays for this period
$query = 'select isbankholiday from calendar where date >= ?  and date ';
#if week period reportstop is sunday
#if month period reportstop is first day of next month so not included
if ($period == 0)
{
  $query .= '<=';
}
else
{
    $query .= '<';
}
$query .= '? and deleted=0';
$query_prm = array($reportstart,$reportstop);
require('inc/doquery.php');
$numbankholidays = $num_results;
$title = d_trad('report');
showtitle($title);
if ($ourparams == '') { $ourparams = $title;}

#to create xls file
$xlsA = array();
?>

<section id="share">
  <?php
  if ($isclickexport == 1) 
  {
    echo '<a href="' . $filepath .'" download="' .$filename .'" class="btn btn-success">' . d_trad('download') . '</a>';
  }
  else
  {
    echo '<a href="printwindow.php?report=hr_badgereport&isclick=1&period=' . $period . '&week_year=' . $week_year . '&mon=' . $month . '&year=' . $year . '&empid=' . $employeeid_save . '&ism=' .$ismanager . '&mydid=' . $myemployeedepartmentid . '&mysid=' . $myemployeesectionid . '" class="btn btn-success">' . d_trad('export') .'</a>';
  }
  ?>
  <a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
</section>
<div id="main">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-2">
        <div class="logo">
          <img class="img-responsive" alt="logo" src="../pics/logo.jpg">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-offset-1 col-xs-15 text-center document-title">
        <h1 class="title text-uppercase">
          <?php echo $ourparams; ?>
        </h1>
      </div>
      <div class="col-xs-offset-1 col-xs-15 text-center document-title">
        <p>
          <strong>
            <?php echo $ourparams2; ?>
          </strong>
        </p>
      </div>
    </div>                
    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <tbody>
            <tr>
              <?php
              if($nbemployees > 1)
              {
                #employee name column
                echo '<td></td>'; array_push($xlsA,'');
              }
              ?>
              <td class="title text-initial"><?php echo d_trad('numtotalhours'); ?></td><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('numtotalhours')));?>
              <td colspan = 4 class="title text-initial"><?php echo d_trad('increasedhours'); ?></td><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('increasedhours')));array_push($xlsA,'');array_push($xlsA,'');array_push($xlsA,'');?>
              <td colspan = 5 class="title text-initial"><?php echo d_trad('overtimehours'); ?></td><?php array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('overtimehours')));array_push($xlsA,'');array_push($xlsA,'');array_push($xlsA,'');array_push($xlsA,'');array_push($xlsA,'');?>
            </tr>
            <?php
            if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA, $CSV_DELIMITER ); }
            #for a new line in file
            $xlsA = array();?>

            <tr>
              <?php
              #Title second line 
              if($nbemployees > 1)
              {
                #employee name column
                echo '<td></td>';array_push($xlsA,'');
              }
              echo '<td></td>';array_push($xlsA,'');
              #increasedhours
              echo '<td class="title3 text-initial">15%</td>';array_push($xlsA,'15%');
              echo '<td class="title3 text-initial">25%</td>';array_push($xlsA,'25%');
              echo '<td class="title3 text-initial">50%</td>';array_push($xlsA,'50%');
              echo '<td class="title3 text-initial">100%</td>';array_push($xlsA,'100%');
              #overtime
              echo '<td class="title3 text-initial">25%</td>';array_push($xlsA,'25%'); 
              echo '<td class="title3 text-initial">50%</td>';array_push($xlsA,'50%'); 
              echo '<td class="title3 text-initial">75%</td>'; array_push($xlsA,'75%');
              echo '<td class="title3 text-initial">100%</td>';array_push($xlsA,'100%'); 
              echo '<td class="title3 text-initial">200%</td>';array_push($xlsA,'200%');
              echo '</tr>';

              if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
              #for a new line in file
              $xlsA = array();
            
              #Title third line 
              echo '<tr>';
              
              if($nbemployees > 1)
              {
                #employee name column
                echo '<td></td>';array_push($xlsA,'');
              }
              echo '<td></td>';array_push($xlsA,'');
              #increasedhours
              echo '<td class="title3 text-initial">' . d_trad('weeknight') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('weeknight')));
              echo '<td class="title3 text-initial">' . d_trad('sundaydaytime') . '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('sundaydaytime')));
              echo '<td class="title3 text-initial">' . d_trad('sundaynighttime') . '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('sundaynighttime')));
              echo '<td class="title3 text-initial">' . d_trad('bankholidays') . '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('bankholidays')));
              #overtime
              $overtime1trad = d_trad('overtime1',array($OVERTIME_FIRST_START_HOUR,$OVERTIME_SECOND_START_HOUR));
              $overtime2trad = d_trad('overtime2',$OVERTIME_SECOND_START_HOUR);
              echo '<td class="title3 text-initial">' . $overtime1trad . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$overtime1trad));
              echo '<td class="title3 text-initial">' . $overtime2trad . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$overtime2trad));
              echo '<td class="title3 text-initial">' . d_trad('weeknight') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('weeknight')));
              echo '<td class="title3 text-initial">' . d_trad('sunday') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('sunday')));
              echo '<td class="title3 text-initial">' . d_trad('bankholidays') . '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('bankholidays')));
              echo '</tr>';
              if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
              #for a new line in file
              $xlsA = array();
# DISPLAY RESULTS
#for each employee = each line
$ismanager_prev = 0;
$nbtotal = 0;
for($e=0;$e<$nbemployees;$e++)
{
  $eid = $employee_todisplayA[$e]['employeeid'];
  $ename = $employeesortedbyteamA[$eid];
  $eismanager = $employeesortedbyteam_ismanagerA[$eid];
  $totalhours = 0;
  $numerrors = 0;
  
  #display total for each team
  /*if($e != 0 && (($ismanager_prev == 0 && $ismanager == 1)))
  {
    echo '<tr>';   
    echo '<td><b>' . d_trad('teamtotal') . '</b></td>';

    for($c=0;$c<$nbcol;$c++)
    {
      echo '<td><b>' . $totalA[$c] . '</b></td>';
      $totalA[$c] = 0;
    }
    
    #reinitialize totals by team
    $grandtotalpresence = $grandtotalabsence = $grandtotalnotvalidated = $smalltotalovertime = $grandtotalovertime = 0;
    
    $nbtotal ++;
  }*/
    
  echo '<tr>';
  if($nbemployees > 1)
  {      
    echo '<td class="text-right">';
    if ($ismanager) { echo '<b>'; }
    if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $eid . '" target=_blank>' .  $ename . '</a>';}
    else { echo $ename; } 
    echo '</td>';array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$ename));
  }
  
  #get the number of daily checking for this category of employee
  $query = 'select ec.numdailylogs from employeecategory ec,employee e where ec.employeecategoryid = e.employeecategoryid and e.employeeid=?';
  $query_prm = array($eid);
  require('inc/doquery.php');
  $numdailylogs = 0;
  if ($num_results > 0)
  {
    $numdailylogs = $query_result[0]['numdailylogs'];
  }
  else
  {
    $numdailylogs = $_SESSION['ds_defaultnumdailylogs'];
  }  
  

  #number total of minutes worked
  $totalminutes = 0;
  #number total of minutes worked during night (except sunday/bank holiday)
  $totalminutesnight = 0; 
  #number total of minutes worked during day (except sunday/bank holiday)  
  $totalminutesday = 0;  
  #number total of minutes worked during bank holiday
  $totalminutesbankholiday = 0; 
  #number total of minutes worked on sunday night (except bank holiday)  
  $totalminutessundaynight = 0; 
  #number total of minutes worked on sunday during the day (except bank holiday)  
  $totalminutessundayday = 0;

  #number total of minutes worked between first and second start overtime (between 40h and 47h a week)
  $totalminutesovertime = 0; 
  #number total of minutes worked between first and second start overtime (between 40h and 47h a week) during daytime 
  $totalminutesovertimeday = 0;
  #number total of minutes worked after second start overtime (after 47h a week)  
  $totalminutesovertime2 = 0;
  #number total of minutes worked after second start overtime (after 47h a week)  during daytime  
  $totalminutesovertimeday2 = 0;  
  #number total of minutes worked overtime by night (except sunday/bank holiday)
  $totalminutesovertimenight = 0; 
  #number total of minutes worked overtime on sunday (except bank holiday)
  $totalminutesovertimesunday = 0; 
  #number total of minutes worked overtime on bank holiday (except sunday)  
  $totalminutesovertimebankholiday = 0;
  
  #when employees works overtime: 2 maximum, first from 40 to 47 hours and second one after 47 hours
  $isovertime = 0; $isovertime2 = 0;

  #get hours for each day of period 
  for($d=1;$d<=$nbdays;$d++)
  {
    $dateday = d_getdateadddays($d-1,$reportstart) ;
    //d_debug('DATE',$dateday);
    #check if there is a bank holiday for this date
    $query = 'select isbankholiday from calendar where date = ? and deleted=0';
    $query_prm = array($dateday);
    require('inc/doquery.php');
    $isbankholiday = 0;
    if ($num_results > 0)
    { 
      $isbankholiday = $query_result[0]['isbankholiday']; 
      //d_debug('FERIE',$isbankholiday);      
    }
    
    #check if it is sunday
    $issunday = d_issunday($dateday);

    $query = 'select * from badgelog where deleted=0 and badgeuserid=? and badgedate=? order by badgetime desc';
    $query_prm = array($employeesortedbyteam_badgenumberA[$eid],$dateday);
    require('inc/doquery.php');
    if ($num_results != $numdailylogs) 
    { 
      $numerrors ++; 
    }
    else
    {
      $r = 0;

      while($r<$num_results && (($r+1) < $num_results))
      {
        #badgetime are ordered desc
        $timestop = $query_result[$r]['badgetime'];
        $timestart = $query_result[$r+1]['badgetime'];
        
        #calculate duration of work 
        $minutes = d_timetominutes($timestop) - d_timetominutes($timestart);
        //d_debug('DE ' .$timestart . ' à ' .$timestop ,$minutes/60); 
        if ($minutes > 0)
        {
          #susbtract lunchtime
          $minutes -= d_timetominutes($ds_lunchtime);        
          $totalminutes += $minutes;
          //d_debug('totalminutes',$totalminutes/60);
          
          #is overtime
          if ((($isovertime == 0 ) && ($totalminutes > $OVERTIME_FIRST_START_HOUR_MINUTES))
              || ($isovertime == 1))
          {
            # calculate how many hours are overtime   
            if ($totalminutes > $OVERTIME_SECOND_START_HOUR_MINUTES) 
            {
              #overtime 40-47h to be added at total 
              $minutesovertime = $OVERTIME_SECOND_START_HOUR_MINUTES - $OVERTIME_FIRST_START_HOUR_MINUTES -$totalminutesovertime;
              #overtime >47h
              $minutesovertime2 = $totalminutes - $OVERTIME_SECOND_START_HOUR_MINUTES;       
              if ($isovertime2 == 0)
              {
                #time from wich we reach first overtime start and second start
                $timeovertime = $timestart;
                $timeovertime2 = d_addminutestotime($timestart,$minutesovertime);
                
                $isovertime2 = 1; 
              }
              else
              {
                #if already overtime2: we start overtime2 at timestart
                $timeovertime2 = $timestart;
                $minutesovertime = 0;
                $minutesovertime2 = $minutes;                
              }
              //d_debug('OVERTIME2, minutesovertime',$minutesovertime/60);
              //d_debug('minutesovertime2',$minutesovertime2/60);
              //d_debug('timeovertime',$timeovertime);
              //d_debug('timeovertime2',$timeovertime2);
            }
            else
            {
              $minutesovertime = $totalminutes - $OVERTIME_FIRST_START_HOUR_MINUTES;
              $overtimeminutes2 = 0;
              
              #time from wich we reach first overtime start
              $timeovertime = d_subtractminutestotime($timestop,$minutesovertime);
              //d_debug('OVERTIME1, minutesovertime',$minutesovertime/60);
              //d_debug('timeovertime',$timeovertime);
            }
            $totalminutesovertime += $minutesovertime;
            //d_debug('totalminutesovertime',$totalminutesovertime/60);          
            $isovertime = 1;
            
            if ($isbankholiday == 1) 
            { 
              $totalminutesovertimebankholiday += $minutesovertime + $minutesovertime2;
              $minutesday = $minutesnight = 0;
              //d_debug('FERIE, totalminutesovertimebankholiday',$totalminutesovertimebankholiday/60);         
            }
            else if ($issunday)
            {
              $totalminutesovertimesunday += $minutesovertime + $minutesovertime2;
              $minutesday = $minutesnight = 0;              
              //d_debug('SUNDAY, totalminutesovertimesunday',$totalminutesovertimesunday/60);              
            }
            else
            {
              #calculate overtime night
              if ($isovertime2 == 1)
              {
                $minutesovertimenight2 = d_getminutesnight($timestop,$timeovertime2,$NIGHT_STOP_TIME,$NIGHT_START_TIME);
                //d_debug('minutesovertime2',$minutesovertime2/60);
                //d_debug('minutesovertimenight2',$minutesovertimenight2/60);
                $totalminutesovertimeday2 = $minutesovertime2 - $minutesovertimenight2;

                $minutesovertimenight = d_getminutesnight($timeovertime2,$timeovertime,$NIGHT_STOP_TIME,$NIGHT_START_TIME);   
                $totalminutesovertimeday += $minutesovertime - $minutesovertimenight;
                
                $totalminutesovertimenight += $minutesovertimenight + $minutesovertimenight2;
                //d_debug('JOUR NORMAL HEURES SUP 40-47, totalminutesovertimeday',$totalminutesovertimeday/60); 
                //d_debug('HEURES SUP 47, totalminutesovertime2',$totalminutesovertimeday2/60); 
                //d_debug('HEURES SUP NUIT, totalminutesovertimenight',$totalminutesovertimenight/60); 
              }
              else if($isovertime == 1)
              {
                $minutesovertimenight = d_getminutesnight($timestop,$timeovertime,$NIGHT_STOP_TIME,$NIGHT_START_TIME);   
                $totalminutesovertimeday += ($minutesovertime - $minutesovertimenight);
                
                $totalminutesovertimenight += $minutesovertimenight;    
                //d_debug('JOUR NORMAL HEURES SUP 40-47, totalminutesovertimeday',$totalminutesovertimeday/60);  
                //d_debug('HEURES SUP NUIT, totalminutesovertimenight',$totalminutesovertimenight/60);              
              }
              #calculate minutes not overtime
              $minutesnight = d_getminutesnight($timeovertime,$timestart,$NIGHT_STOP_TIME,$NIGHT_START_TIME);     
              $minutesday = $minutes - $minutesovertime - $minutesovertime2 - $minutesnight;
            }
          }
          else
          {
            $minutesnight = d_getminutesnight($timestop,$timestart,$NIGHT_STOP_TIME,$NIGHT_START_TIME);          
            $minutesday = $minutes - $minutesnight;
          }
          //d_debug('minutesday',$minutesday/60);
          //d_debug('minutesnight',$minutesnight/60);
          
          if (($minutesday != 0) || ($minutesnight != 0))
          {
            if ($isbankholiday == 1) 
            { 
              $totalminutesbankholiday += $minutesday + $minutesnight; #minutesday + minutesnight can be different from $minutes when overtime)
              //d_debug('FERIE, totalminutesbankholiday',$totalminutesbankholiday/60);                
            }
            else if ($issunday)
            {
              $totalminutessundayday += $minutesday;
              $totalminutessundaynight += $minutesnight;
              //d_debug('DIMANCHE jour, totalminutessundayday',$totalminutessundayday/60);             
              //d_debug('DIMANCHE NUIT, totalminutessundaynight',$totalminutessundaynight/60);             
            }
            else
            {
              $totalminutesnight += $minutesnight;
              //d_debug('HEURES NUIT, totalminutesnight',$totalminutesnight/60);             
            }
          }
        }
    
        $r += 2;
      }
    }
  }
  
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutes,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutes,1));  
  #increased hours
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesnight,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesnight,1)); 
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutessundayday,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutessundayday,1));
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutessundaynight,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutessundaynight,1));     
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesbankholiday,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesbankholiday,1)); 
  #overtime  
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesovertimeday,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesovertimeday,1));     
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesovertimeday2,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesovertimeday2,1));     
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesovertimenight,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesovertimenight,1));     
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesovertimesunday,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesovertimesunday,1));     
  echo '<td class="text-center">' . d_displayhourminfrommin($totalminutesovertimebankholiday,0) . '</td>'; array_push($xlsA,d_displayhourminfrommincsv($totalminutesovertimebankholiday,1));     
  //echo '<td class="text-center">' . $numerrors . '</td></tr>'; array_push($xlsA,$numerrors);      
  
  $ismanager_prev = $ismanager;
  
  if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA , $CSV_DELIMITER); }
  $xlsA = array();
}//for $employeeid

#TOTAL
/*if($nbemployees > 1)
{   
  #total for last team
  echo '<tr>';   
  echo '<td><b>' . d_trad('teamtotal') . '</b></td>';

  for($c=0;$c<$nbcol;$c++)
  {
    echo '<td><b>' . $totalA[$c] . '</b></td>';
    $totalA[$c] = 0;
  }
  echo '</tr>';
  
  #reinitialize totals by team
  $grandtotalpresence = $grandtotalabsence = $grandtotalnotvalidated = $smalltotalovertime = $grandtotalovertime = 0;
  
  if($nbtotal > 1)
  {
    echo '<tr>';   
    echo '<td><b>' . d_trad('grandtotal') . '</b></td>';

    for($c=0;$c<$nbcol;$c++)
    {
      echo '<td><b>' . $grandtotalA[$c] . '</b></td>';
    }
    #no total if planningteamvalue chosen
    if( $isuniqueplanningteamvalue == 0)
    {    
      echo '<td><b>' . $verygrandtotalpresence . '</b></td>';
      echo '<td><b>' . $verygrandtotalabsence . '</b></td>';
      // if ($ds_ismanagervalidationplanning == 1)
      // {
        echo '<td><b>' . $verygrandtotalnotvalidated . '</b></td>';
      // }
    }
    echo '<td><b>' . d_displayovertime($verysmalltotalovertime) . '</b></td>';
    echo '<td><b>' . d_displayovertime($verygrandtotalovertime) . '</b></td>';
    if ( $ds_planningteamcommentcolumn == 1 ) 
    {
      echo '<td><b>&nbsp;</b></td>';
    }
    echo '</tr>';
  }
}*/
if (($isclickexport == 1) && $file) { fclose($file);}
?>
          </tbody>
        </table>
</div>
</div>
</body>
</html>
</table>
