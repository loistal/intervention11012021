<style>
form {
  max-width: 100vw;
}

.container {
    /* overflow-x: scroll; client keeps changing their mind about this */
    margin-left: 90px;
}

.fixed {
    position: absolute;
    left: 0;
    border: none !important;
    margin-top: 5px;
}
</style>

<?php

$buffer = '';
$lockme = 1;
if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser']) { $lockme = 0; }

$showovertimecalc = 0; if ($_SESSION['ds_ishrsuperuser'] == 1)
{
  $showovertimecalc = 1; $showoct = '';
  $weekday_textA[1] = 'Lundi';
  $weekday_textA[2] = 'Mardi';
  $weekday_textA[3] = 'Mercredi';
  $weekday_textA[4] = 'Jeudi';
  $weekday_textA[5] = 'Vendredi';
  $weekday_textA[6] = 'Samedi';
  $weekday_textA[7] = 'Dimanche';
}

function showtimeworked ($timeworked)
{
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  return $showtimeworked;
}

require('inc/func_planning.php');

$PA['year'] = 'int';
$PA['month'] = 'int';
$PA['employeeid'] = '';
require('inc/readpost.php');

require('preload/employee.php');
require('preload/absence_reason.php');

if ($employeeid < 1 || !array_key_exists($employeeid, $employeeA)) { exit; }

$empty_day = array_fill(0, 1440, 0); # whole day

$total_meal_allowance = 0;
$em_minutes_worked = 0;
$em_minutes_to_pay = 0;
$em = array();
$nonworkedA = array(); $nonworkedA[1] = $nonworkedA[2] = $nonworkedA[3] = $nonworkedA[4] = $nonworkedA[5] = 0;

$badgeuserid = 0;
$query = 'select badgenumber,hourspermonth from employee where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
$hourspermonth = $query_result[0]['hourspermonth'];
###
$hoursperweek = 0;
if ($hourspermonth == 169) { $hoursperweek = 39; } # TODO need a good way to define this!!
if ($hourspermonth == 130) { $hoursperweek = 30; }
if ($hourspermonth == 84.5) { $hoursperweek = 19.5; }
$minutesperweek = $hoursperweek * 60;
###
if ($_SESSION['ds_time_management'] == 1)
{
  $badgeuserid_field = 'badgeuserid';
  $badgeuserid = $query_result[0]['badgenumber'];
}
if ($badgeuserid < 1) { $badgeuserid = $employeeid; $badgeuserid_field = 'employeeid'; }

$buffer .= '<h2>Tableau de pointage: '.$employeeA[$employeeid].' - '.$month.' / '.$year.'</h2>';
showtitle('Tableau de pointage: '.$employeeA[$employeeid].' - '.$month.' / '.$year);

$date = date_create(d_builddate(1,$month,$year));

$lastmonth = $month - 1;
$lastyear = $year;
if ($lastmonth == 0) { $lastmonth = 12; $lastyear--; }

if ($_SESSION['ds_payroll_startday'])
{
  $date = date_create(d_builddate($_SESSION['ds_payroll_startday'],$lastmonth,$lastyear));
}

$first_day = date_format($date, 'N');
$days_previous_month = $first_day - 1;
$date2 = date_create(d_builddate(cal_days_in_month(CAL_GREGORIAN, $month, $year),$month,$year));
$last_day = date_format($date2, 'N');
$days_next_month = 7 - $last_day;
$num_days = $days_previous_month + cal_days_in_month(CAL_GREGORIAN, $month, $year) + $days_next_month;
date_sub($date, date_interval_create_from_date_string($days_previous_month.' days'));
$date2 = clone $date;
$numdailylogs = $_SESSION['ds_defaultnumdailylogs'];
$totalshown = 0;

if ($_SESSION['ds_payroll_startday'])
{
  $num_days++;$num_days++;
}

$query = 'delete from employee_month_seq where employeeid=? and year(sequencedate)=? and month(sequencedate)=?';
$query_prm = array($employeeid,$year,$month);
require('inc/doquery.php');

if (isset($_POST['update']))
{
  foreach ($_POST as $badgelogid => $time)
  {
    if (strpos($badgelogid, 'badgelogid') === 0)
    {
      $badgelogid = substr($badgelogid,10);
      if ($badgelogid > 0)
      {
        if ($time == '')
        {
          $query = 'update badgelog set deleted=1 where badgelogid=?';
          $query_prm = array($badgelogid);
          require('inc/doquery.php');
        }
        else
        {
          $query = 'select badgetime from badgelog where badgelogid=?';
          $query_prm = array($badgelogid);
          require('inc/doquery.php');
          if (d_displaytime($query_result[0]['badgetime']) != $time)
          {
            $query = 'update badgelog set ismanual=1,badgetime=? where badgelogid=?';
            $query_prm = array($time,$badgelogid);
            require('inc/doquery.php');
          }
        }
      }
    }
  }
  for ($i=0; $i < $num_days; $i++)
  {
    if ($i > 0) { date_add($date2, date_interval_create_from_date_string('1 day')); }
    $dateinsert = d_builddate($i,$month,$year);
    if ($_SESSION['ds_payroll_startday'] && $i >= $_SESSION['ds_payroll_startday'])
    {
      $temp_month = $month-1;
      $temp_year = $year;
      if ($temp_month == 0) { $temp_month = 12; $temp_year--; }
      $dateinsert = d_builddate($i,$temp_month,$temp_year);
    }
    #echo $i,' ',date_format($date2,'Y-m-d'),' ',$dateinsert,'<br>';
    for ($y=0; $y < $numdailylogs; $y++)
    {
      $namestring = 'new'.$i.'_'.$y;
      if (isset($_POST[$namestring]) && $_POST[$namestring] != '') # TODO check valid time format
      {
        $time = $_POST[$namestring];
        $query = 'insert into badgelog (ismanual,badgedate,badgetime,'.$badgeuserid_field.') values (1,?,?,?)';
        $query_prm = array($dateinsert,$time,$badgeuserid);
        require('inc/doquery.php');
      }
    }
    for ($y=1; $y <= $numdailylogs; $y++)
    {
      $namestring = 'worked'.$y.'_'.$i;
      if (isset($_POST[$namestring])) # TODO check valid time format
      {
        $time = $_POST[$namestring]; if ($time == '00:00:00') { $time = NULL; }
        $query = 'update badge_employeemonth set worked'.$y.'=? where employeeid=? and reportdate=?';
        $query_prm = array($time,$employeeid,$dateinsert);
        require('inc/doquery.php');
      }
    }
    for ($y=1; $y <= 4; $y++)
    {
      $namestring = 'nonworked'.$y.'_'.$i;
      if (isset($_POST[$namestring])) # TODO check valid time format
      {
        $time = $_POST[$namestring]; if ($time == '00:00:00') { $time = NULL; }
        $query = 'update badge_employeemonth set nonworked'.$y.'=? where employeeid=? and reportdate=?';
        $query_prm = array($time,$employeeid,$dateinsert);
        require('inc/doquery.php');
      }
    }
    $namestring = 'nonworkedsub_'.$i;
    if (isset($_POST[$namestring])) # TODO check valid time format
    {
      $time = $_POST[$namestring]; if ($time == '00:00:00') { $time = NULL; }
      $query = 'update badge_employeemonth set nonworkedsub=?,meal_allowance=? where employeeid=? and reportdate=?';
      $query_prm = array($time,(int) $_POST['meal_allowance_'.$i],$employeeid,$dateinsert);
      require('inc/doquery.php');
    }
    if (isset($_POST['managerjournal'.$i]) && $_POST['managerjournal'.$i] != '')
    {
      $query = 'select employee_dayid from employee_day where employeeid=? and employeeday=?';
      $query_prm = array($employeeid,date_format($date2,'Y-m-d'));
      require('inc/doquery.php');
      if ($num_results)
      {
        $query = 'update employee_day set managerjournal=? where employee_dayid=?';
        $query_prm = array($_POST['managerjournal'.$i],$query_result[0]['employee_dayid']);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into employee_day (managerjournal,employeeid,employeeday) values(?,?,?)';
        $query_prm = array($_POST['managerjournal'.$i],$employeeid,date_format($date2,'Y-m-d'));
        require('inc/doquery.php');
      }
    }
  }
  $query = 'select month_weeklyhoursid from month_weeklyhours where employeeid=? and year=? and month=?';
  $query_prm = array($employeeid, $year, $month);
  require('inc/doquery.php');
  if (!isset($_POST['weeklyhours5id'])) { $_POST['weeklyhours5id'] = 0; }
  if (!isset($_POST['weeklyhours6id'])) { $_POST['weeklyhours6id'] = 0; }
  if ($num_results)
  {
    $month_weeklyhoursid = $query_result[0]['month_weeklyhoursid'];
    $query = 'update month_weeklyhours set weeklyhours1id=?,weeklyhours2id=?,weeklyhours3id=?,weeklyhours4id=?,weeklyhours5id=?,weeklyhours6id=?
    where month_weeklyhoursid=?';
    $query_prm = array($_POST['weeklyhours1id'],$_POST['weeklyhours2id'],$_POST['weeklyhours3id'],$_POST['weeklyhours4id'],$_POST['weeklyhours5id'],$_POST['weeklyhours6id'],$month_weeklyhoursid);
    require('inc/doquery.php');
  }
  else
  {
    $query = 'insert into month_weeklyhours (employeeid,year,month,weeklyhours1id,weeklyhours2id,weeklyhours3id,weeklyhours4id,weeklyhours5id,weeklyhours6id)
    values (?,?,?,?,?,?,?,?,?)';
    $query_prm = array($employeeid,$year,$month,$_POST['weeklyhours1id'],$_POST['weeklyhours2id'],$_POST['weeklyhours3id'],$_POST['weeklyhours4id'],$_POST['weeklyhours5id'],$_POST['weeklyhours6id']);
    require('inc/doquery.php');
  }
  # applying weeklyhours MOVED INLINE, SEE BELOW
}

$weeklyhours_counter = 1;
$weeklyhours1id = 0; $weeklyhours2id = 0; $weeklyhours3id = 0; $weeklyhours4id = 0; $weeklyhours5id = 0; $weeklyhours6id = 0;
$query = 'select weeklyhours1id,weeklyhours2id,weeklyhours3id,weeklyhours4id,weeklyhours5id,weeklyhours6id from month_weeklyhours where employeeid=? and year=? and month=?';
$query_prm = array($employeeid, $year, $month);
require('inc/doquery.php');
if ($num_results)
{
  $weeklyhours1id = $query_result[0]['weeklyhours1id'];
  $weeklyhours2id = $query_result[0]['weeklyhours2id'];
  $weeklyhours3id = $query_result[0]['weeklyhours3id'];
  $weeklyhours4id = $query_result[0]['weeklyhours4id'];
  $weeklyhours5id = $query_result[0]['weeklyhours5id'];
  $weeklyhours6id = $query_result[0]['weeklyhours6id'];
}

$buffer .= '<form method="post" action="reportwindow.php"><div class="container"><table class=report>';
$buffer .= '<thead><th class="fixed"><th colspan=2>Pointage 1<th colspan=2>Pointage 2<th colspan=2>Pointage 3<th><th colspan=2>TTE – Séquence 1<th colspan=2>TTE – Séquence 2<th colspan=2>TTE – Séquence 3<th>
<th><font size=-1>Congés Payé</font><th><font size=-1>Récup<th><font size=-1>Mal/Acc/Mat<th><font size=-1>Divers<th><font size=-1>Ajouter<th><font size=-1>Heures<th><font size=-1>Panier';
if ($showovertimecalc)
{
  $buffer .= '<th><font size=-1>Maj 15%'; # stupid hardcode TODO dynamic
  $buffer .= '<th><font size=-1>Maj 25%';
  $buffer .= '<th><font size=-1>Maj 50%';
  $buffer .= '<th><font size=-1>Maj 100%';
  $buffer .= '<th><font size=-1>Sup 25%';
  $buffer .= '<th><font size=-1>Sup 50%';
  $buffer .= '<th><font size=-1>Sup 65%';
  $buffer .= '<th><font size=-1>Sup 75%';
  $buffer .= '<th><font size=-1>Sup 100%';
  $buffer .= '<th><font size=-1>Sup 200%';
}
$buffer .= '<th><font size=-1>Absences';
if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser']) { $buffer .= '<th><font size=-1>Remarques'; }
$buffer .= '</thead>'; # 4 categories should be read from absencecategory table
$timeworked_weekly = 0; $timeworked_weekly2 = 0; $timeworked_weekly3 = 0;
$timeworked_monthly = 0; $timeworked_monthly2 = 0; $timeworked_monthly3 = 0;
$compare_month = $month+1; if ($compare_month > 12) { $compare_month = 1; }
for ($i=0; $i < $num_days; $i++)
{
  if ($i > 0) { date_add($date, date_interval_create_from_date_string('1 day')); }
  $date_compare = date_format($date, 'Y-m-d');
  $weekday = date_format($date, 'N');
  $timeworked = 0;
  $this_month = 0;
  if ($_SESSION['ds_payroll_startday'])
  {
    if (date_format($date, 'm') == $month && date_format($date, 'j') < $_SESSION['ds_payroll_startday']) { $this_month = 1; }
    elseif (date_format($date, 'm') == $lastmonth && date_format($date, 'j') >= $_SESSION['ds_payroll_startday']) { $this_month = 1; }
  }
  elseif (date_format($date, 'm') == $month) { $this_month = 1; }
  
  if ($this_month && isset($_POST['apply_weeklyhours'.$weeklyhours_counter.'id']) && $_POST['apply_weeklyhours'.$weeklyhours_counter.'id'] == 1 && $_POST['weeklyhours'.$weeklyhours_counter.'id'] > 0)
  {
    $query = 'select * from weeklyhours where weeklyhoursid=?';
    $query_prm = array($_POST['weeklyhours'.$weeklyhours_counter.'id']);
    require('inc/doquery.php');
    $main_result = $query_result;
    for ($y=1; $y <= 6; $y++)
    {
      $time = $main_result[0]['weeklyhour'.date_format($date, 'N').'_'.$y]; if ($time == '00:00:00') { $time = NULL; }
      $query = 'update badge_employeemonth set worked'.$y.'=? where employeeid=? and reportdate=?';
      $query_prm = array($time,$employeeid,$date_compare);
      require('inc/doquery.php');
    }
  }
  
  if (date_format($date, 'd') == 1 && date_format($date, 'm') == $compare_month) # copy at bottom
  {
    $totalshown = 1; # added quick exception if week ends same time as month, see below
    $buffer .= '<tr><td class="fixed"><b>Total mois';
    for ($y=0; $y<6; $y++) { $buffer .= '<td>'; }
    $buffer .= '<td align=right><b>' . showtimeworked($timeworked_monthly);
    for ($y=0; $y<6; $y++) { $buffer .= '<td>'; }
    $buffer .= '<td align=right><b>' . showtimeworked($timeworked_monthly2);
    $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[1]);
    $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[2]);
    $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[3]);
    $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[4]);
    $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[5]);
    $buffer .= '<td align=right><b>'. showtimeworked($timeworked_monthly3);
    $buffer .= '<td>';
    if ($showovertimecalc) { $buffer .= '<td colspan=9>'; }
    $buffer .= '<td>';
    if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser']) { $buffer .= '<td>'; }
  }
   
  $showday = date_format($date, 'D'); # ridiculuous there is no func for this, strftime does NOT work
  if ($showday == 'Mon') { $showday = 'Lun'; }
  elseif ($showday == 'Tue') { $showday = 'Mar'; }
  elseif ($showday == 'Wed') { $showday = 'Mer'; }
  elseif ($showday == 'Thu') { $showday = 'Jeu'; }
  elseif ($showday == 'Fri') { $showday = 'Ven'; }
  elseif ($showday == 'Sat') { $showday = 'Sam'; }
  elseif ($showday == 'Sun') { $showday = 'Dim'; }
  $buffer .= '<tr><td class="fixed">'.$showday.' '.substr(datefix2($date_compare),0,-5);
  
  ### 
  $query = 'select isbankholiday from calendar where date=?';
  $query_prm = array($date_compare);
  require('inc/doquery.php');
  if ($num_results) { $d_holidayA[$weekday] = (int) $query_result[0]['isbankholiday']; }
  else { $d_holidayA[$weekday] = 0; }
  if ($date_compare == d_builddate(8,5,$year)) { $d_holidayA[$weekday] = 0; } # exception for May 8
  ###
  
  # read in sequance for the day, up to 6 events
  $query = 'select * from badgelog where deleted=0 and badgetime is not null and '.$badgeuserid_field.'=? and badgedate=? order by badgedate,badgetime';
  $query_prm = array($badgeuserid,date_format($date,'Y-m-d'));
  require('inc/doquery.php');
  $first_empty_result = 0; $style_alert = '';
  for ($y=0; $y < $numdailylogs; $y++)
  {
    if (isset($query_result[$y]))
    {
      $timeA[$y] = d_displaytime($query_result[$y]['badgetime']);
      if ($this_month)
      {
        $namestring = 'badgelogid'.$query_result[$y]['badgelogid'];
        if ($lockme) { $buffer .= '<td align=right>'.d_displaytime($query_result[$y]['badgetime']); }
        else
        {
          $buffer .= '<td><input type="time"';
          if ($query_result[$y]['ismanual'] == 1) { $buffer .= ' style="color: blue"'; } # TODO color
          $buffer .= ' name="'.$namestring.'" value="'.d_displaytime($query_result[$y]['badgetime']).'">';
        }
      }
      else
      {
        $buffer .= '<td align=right>'.d_displaytime($query_result[$y]['badgetime']);
      }
    }
    else
    {
      if ($first_empty_result == 0 && $y%2) { $style_alert = ' style="background:#00FFFF;"'; } # TODO color
      $first_empty_result = 1;
      $timeA[$y] = 0;
      if ($this_month)
      {
        $kladd = (int) date_format($date, 'd');
        if ($lockme) { $buffer .= '<td>'; }
        else { $buffer .= '<td><input type="time"'.$style_alert.' name="new'.$kladd.'_'.$y.'">'; }
        $style_alert = '';
      }
      else
      {
        $buffer .= '<td>&nbsp;';
      }
    }
    if ($y%2 == 1 && $timeA[$y] !== 0 && $timeA[$y-1] !== 0)
    {
      $timeworked += 60 * (substr($timeA[$y],0,2) - substr($timeA[$y-1],0,2)) + substr($timeA[$y],3,2) - substr($timeA[$y-1],3,2);
    }
  }
  
  $timeworked_weekly += $timeworked;
  if ($this_month) { $timeworked_monthly += $timeworked; }
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  $buffer .= '<td align=right>'.$showtimeworked;

  $query = 'select * from badge_employeemonth where employeeid=? and reportdate=?';
  $query_prm = array($employeeid,date_format($date,'Y-m-d'));
  require('inc/doquery.php');
  if ($num_results) { $main_result = $query_result; }
  else
  {
    $query = 'insert into badge_employeemonth (employeeid, reportdate) values (?,?)';
    $query_prm = array($employeeid,date_format($date,'Y-m-d'));
    require('inc/doquery.php');
  }

  $timeworked = 0;
  $kladd = (int) date_format($date, 'd');
  $dA[$weekday] = $empty_day;

  for ($y=1; $y <= $numdailylogs; $y++)
  {
    if (!isset($main_result[0]['worked'.$y]) || $main_result[0]['worked'.$y] == '00:00:00') { $main_result[0]['worked'.$y] = ''; }
    if ($this_month && $lockme == 0) { $buffer .= '<td><input type="time" step=60 name="worked'.$y.'_'.$kladd.'" value="'.$main_result[0]['worked'.$y].'">'; }
    else { $buffer .= '<td align=right>'.substr($main_result[0]['worked'.$y],0,5); }
    $kladd2 = 'worked'.($y-1);
    if (isset($main_result[0][$kladd2]) && $y%2 == 0 && $main_result[0]['worked'.$y] != '' && $main_result[0]['worked'.($y-1)] != '')
    {
      $timeworked += 60 * substr($main_result[0]['worked'.$y],0,2);
      $timeworked -= 60 * substr($main_result[0][$kladd2],0,2);
      $timeworked += substr($main_result[0]['worked'.$y],3,2);
      $timeworked -= substr($main_result[0][$kladd2],3,2);
      ### time calc
      $point1 = 60 * substr($main_result[0][$kladd2],0,2) + substr($main_result[0][$kladd2],3,2);
      $point2 = 60 * substr($main_result[0]['worked'.$y],0,2) + substr($main_result[0]['worked'.$y],3,2);
      $period_length = $point2 - $point1;
      if ($this_month) { $em_minutes_worked += $period_length; }
      $periodA = array_fill($point1, $period_length, 1);
      $dA[$weekday] = array_replace($dA[$weekday], $periodA);
      if ($this_month) # sequance de travail
      {
        $seq_length = 120; # night
        if ($point2 >= 360 && $point2 < 1200) { $seq_length = 180; } # day
        if ($period_length < $seq_length)
        {
          # TODO find rate? is it even possible to have one rate?
          $query = 'insert into employee_month_seq (employeeid,sequencedate,begin,end) values (?,?,?,?)';
          $query_prm = array($employeeid,date_format($date,'Y-m-d'),$point1,$point2);
          require('inc/doquery.php');
        }
      }
      ###
    }
  }
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  $buffer .= '<td align=right>'.$showtimeworked;
  $timeworked_weekly2 += $timeworked;
  if ($this_month) { $timeworked_monthly2 += $timeworked; }
  
  if (!isset($main_result[0]['nonworked1']) || $main_result[0]['nonworked1'] == '00:00:00') { $main_result[0]['nonworked1'] = ''; }
  if (!isset($main_result[0]['nonworked2']) || $main_result[0]['nonworked2'] == '00:00:00') { $main_result[0]['nonworked2'] = ''; }
  if (!isset($main_result[0]['nonworked3']) || $main_result[0]['nonworked3'] == '00:00:00') { $main_result[0]['nonworked3'] = ''; }
  if (!isset($main_result[0]['nonworked4']) || $main_result[0]['nonworked4'] == '00:00:00') { $main_result[0]['nonworked4'] = ''; }
  if (!isset($main_result[0]['nonworkedsub']) || $main_result[0]['nonworkedsub'] == '00:00:00') { $main_result[0]['nonworkedsub'] = ''; }
  if ($this_month && $lockme == 0)
  {
    $buffer .= '
    <td><input type="time" step=60 name="nonworked1_'.$kladd.'" value="'.$main_result[0]['nonworked1'].'">
    <td><input type="time" step=60 name="nonworked2_'.$kladd.'" value="'.$main_result[0]['nonworked2'].'">
    <td><input type="time" step=60 name="nonworked3_'.$kladd.'" value="'.$main_result[0]['nonworked3'].'">
    <td><input type="time" step=60 name="nonworked4_'.$kladd.'" value="'.$main_result[0]['nonworked4'].'">';
  }
  else
  {
    $buffer .= '<td align=right>'.substr($main_result[0]['nonworked1'],0,5);
    $buffer .= '<td align=right>'.substr($main_result[0]['nonworked2'],0,5);
    $buffer .= '<td align=right>'.substr($main_result[0]['nonworked3'],0,5);
    $buffer .= '<td align=right>'.substr($main_result[0]['nonworked4'],0,5);
  }
  $d_nonworkedA[$weekday] = 0;
  if (isset($main_result[0]['nonworked1']))
  {
    $timeworked += 60 * (double) substr($main_result[0]['nonworked1'],0,2) + (double) substr($main_result[0]['nonworked1'],3,2);
    $d_nonworkedA[$weekday] += 60 * (double) substr($main_result[0]['nonworked1'],0,2) + (double) substr($main_result[0]['nonworked1'],3,2);
    if ($this_month) { $nonworkedA[1] += 60 * (double) substr($main_result[0]['nonworked1'],0,2) + (double)   substr($main_result[0]['nonworked1'],3,2); }
  }
  if (isset($main_result[0]['nonworked2']))
  {
    $timeworked += 60 * (double) substr($main_result[0]['nonworked2'],0,2) + (double) substr($main_result[0]['nonworked2'],3,2);
    $d_nonworkedA[$weekday] += 60 * (double) substr($main_result[0]['nonworked2'],0,2) + (double) substr($main_result[0]['nonworked2'],3,2);
    if ($this_month) { $nonworkedA[2] += 60 * (double) substr($main_result[0]['nonworked2'],0,2) + (double) substr($main_result[0]['nonworked2'],3,2); }
  }
  if (isset($main_result[0]['nonworked3']))
  {
    $timeworked += 60 * (double) substr($main_result[0]['nonworked3'],0,2) + (double) substr($main_result[0]['nonworked3'],3,2);
    $d_nonworkedA[$weekday] += 60 * (double) substr($main_result[0]['nonworked3'],0,2) + (double) substr($main_result[0]['nonworked3'],3,2);
    if ($this_month) { $nonworkedA[3] += 60 * (double) substr($main_result[0]['nonworked3'],0,2) + (double) substr($main_result[0]['nonworked3'],3,2); }
  }
  if (isset($main_result[0]['nonworked4']))
  {
    $timeworked += 60 * (double) substr($main_result[0]['nonworked4'],0,2) + (double) substr($main_result[0]['nonworked4'],3,2);
    $d_nonworkedA[$weekday] += 60 * (double) substr($main_result[0]['nonworked4'],0,2) + (double) substr($main_result[0]['nonworked4'],3,2);
    if ($this_month) { $nonworkedA[4] += 60 * (double) substr($main_result[0]['nonworked4'],0,2) + (double) substr($main_result[0]['nonworked4'],3,2); }
  }
  
  if ($this_month && $lockme == 0) { $buffer .= '<td><input type="time" step=60 name="nonworkedsub_'.$kladd.'" value="'.$main_result[0]['nonworkedsub'].'">'; }
  else { $buffer .= '<td align=right>&nbsp;'.substr($main_result[0]['nonworkedsub'],0,5); } # &nbsp; to prevent line collapse
  if (isset($main_result[0]['nonworkedsub']))
  {
    $timeworked += 60 * (double) substr($main_result[0]['nonworkedsub'],0,2) + (double) substr($main_result[0]['nonworkedsub'],3,2);
    $d_nonworkedA[$weekday] += 60 * (double) substr($main_result[0]['nonworkedsub'],0,2) + (double) substr($main_result[0]['nonworkedsub'],3,2);
    if ($this_month) { $nonworkedA[5] += 60 * (double) substr($main_result[0]['nonworkedsub'],0,2) + (double) substr($main_result[0]['nonworkedsub'],3,2); }
  }
  $timeworked_weekly3 += $timeworked;
  if ($this_month) { $timeworked_monthly3 += $timeworked; }
  $showtimeworked = $timeworked%60; if ($showtimeworked < 10) { $showtimeworked = '0'.$showtimeworked; }
  $showtimeworked = floor($timeworked/60).':'.$showtimeworked;
  if ($showtimeworked == '0:00') { $showtimeworked = ''; }
  $buffer .= '<td align=right>'.$showtimeworked;
  
  # "Prime de panier" - Meal allowance
  # TODO only allow entry for current month
  if (!isset($main_result[0]['meal_allowance']) || $main_result[0]['meal_allowance'] == 0) { $main_result[0]['meal_allowance'] = ''; }
  if ($this_month && $lockme == 0)
  {
    $total_meal_allowance += (double) $main_result[0]['meal_allowance'];
    $buffer .= '<td><input type="text" STYLE="text-align:right" size=3 name="meal_allowance_'.$kladd.'" value="'.$main_result[0]['meal_allowance'].'">';
  }
  else { $buffer .= '<td align=right>&nbsp;'.$main_result[0]['meal_allowance']; }

  if ($showovertimecalc)# HERE
  {
    if ($this_month)
    {
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_maj15##'; # stupid hardcode TODO dynamic
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_maj25##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_maj50##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_maj100##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup25##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup50##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup65##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup75##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup100##';
      $buffer .= '<th><font size=-1>##'.date_format($date, 'd').'_sup200##';
    }
    else { $buffer .= '<td align=right colspan=9>'; }
  }
  
  $buffer .= '<td>';
  $query = 'select absence_reasonid,ampm,absence_request_comment from absence_request where employeeid=? and startdate<=? and stopdate>=? and accepted=1';
  $query_prm = array($employeeid,date_format($date,'Y-m-d'),date_format($date,'Y-m-d'));
  require('inc/doquery.php');
  if ($num_results)
  {
    for ($y=0; $y < $num_results; $y++)
    {
      $buffer .= $absence_reasonA[$query_result[$y]['absence_reasonid']];
      if ($query_result[$y]['ampm'] == 1) { $buffer .= ' Matin'; }
      elseif ($query_result[$y]['ampm'] == 2) { $buffer .= ' Après-midi'; }
      if ($query_result[$y]['absence_request_comment'] != '') { $buffer .= ' '.d_output($query_result[0]['absence_request_comment']); }
    }
  }
  if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser'])
  {
    $managerjournal = '';
    $query = 'select managerjournal from employee_day where employeeid=? and employeeday=?';
    $query_prm = array($employeeid,date_format($date,'Y-m-d'));
    require('inc/doquery.php');
    if ($num_results) { $managerjournal = $query_result[0]['managerjournal']; }
    if ($this_month)
    {
      $buffer .= '<td><input type=text name="managerjournal'.$i.'" value="'.d_input($managerjournal).'">';
    }
    else { $buffer .= '<td>'.d_output($managerjournal); }
  }
  
  # subtotal after sunday
  if (date_format($date, 'N') == 7)
  {
    $buffer .= '<tr><td class="fixed"><b>Semaine '. date_format($date, 'W');
    for ($y=0; $y<6; $y++) { $buffer .= '<td>'; }
    $buffer .= '<td align=right><b>' . showtimeworked($timeworked_weekly);

    $buffer .= '<td colspan=2>';
    if ($lockme == 0)
    {
      $buffer .= '<input type=checkbox name="apply_weeklyhours'.$weeklyhours_counter.'id" value=1> Appliquer : ';
      $kladd = 'weeklyhours'.$weeklyhours_counter.'id'; $dp_selectedid = $$kladd; $dp_colspan = 4;
      $dp_addtoid = $weeklyhours_counter; $dp_buffername = 'buffer';
      $dp_itemname = 'weeklyhours'; require('inc/selectitem.php');
      $weeklyhours_counter++;
    }
    
    $buffer .= '<td align=right><b>' . showtimeworked($timeworked_weekly2);
    for ($y=0; $y<5; $y++) { $buffer .= '<td>'; }
    $buffer .= '<td align=right><b>' . showtimeworked($timeworked_weekly3);
    $timeworked_weekly = 0; $timeworked_weekly2 = 0; $timeworked_weekly3 = 0;
    
    ### overtime calc
    #$buffer .= array_sum($dA[1])+array_sum($dA[2])+array_sum($dA[3])+array_sum($dA[4])+array_sum($dA[5])+array_sum($dA[6])+array_sum($dA[7]);
    $premiumA = array(); $premium_minutesA = array();
    $overtimeA = array(); $overtime_minutesA = array();
    $running_total = 0; $dayrate_extra_count = 0;
    $overtime = 0;
    for ($weekday=1; $weekday <= 7; $weekday++)
    {
      $new_running_total = $running_total + array_sum($dA[$weekday]);
      if ($new_running_total > $minutesperweek)
      {
        if ($overtime == 0)
        {
          $overtime = 1;
          $overtime_minutes = $new_running_total - $minutesperweek;
          # find exact point where overtime starts and apply different rate if applicable
          $x = 1440;
          while ($overtime_minutes > 0)
          {
            $x--;
            if (isset($dA[$weekday][$x]) && $dA[$weekday][$x] == 1) { $overtime_minutes--; }
            if ($x < 0) { $overtime_minutes = 0; $x = 0; }
          }
          #$buffer .= 'overtime starts at ',$x,'<br>';
        }
        else { $x = 0; }
        # then truncate normal array with 0 and create special partial array to apply overtime rate (whichever higher)
        $d_overtimeA[$weekday] = $dA[$weekday];
        $tempA = array_fill($x, 1440-$x, 0); # was 1140
        $dA[$weekday] = array_replace($dA[$weekday], $tempA);
        $tempA = array_fill(0, $x, 0);
        $d_overtimeA[$weekday] = array_replace($d_overtimeA[$weekday], $tempA);
      }
      if ($weekday == 1)
      {
        $tosub = 7 - $weekday;
        $testdate = $date;
        date_sub($testdate, date_interval_create_from_date_string($tosub.' day'));
      }
      else
      {
        date_add($testdate, date_interval_create_from_date_string('1 day'));
      }
      if ($this_month) # only if day is within month
      {
        $dayrate = 100; # TODO rates are only for Air Archipels
        $nightrate = 115;
        if ($weekday == 7) { $dayrate = 125; $nightrate = 150; }
        if ($d_holidayA[$weekday]) { $dayrate = 200; $nightrate = 200; }
        #$buffer .= $dayrate, ' ',$nightrate,'<br>';
        $night_early = array_slice($dA[$weekday], 0, 360);
        $day_kladd = array_slice($dA[$weekday], 360, 840);
        $night_late = array_slice($dA[$weekday], 1200, 240);
        if (!isset($premiumA[$nightrate])) { $premiumA[$nightrate] = 0; }
        if (!isset($premiumA[$dayrate])) { $premiumA[$dayrate] = 0; }
        $premiumA[$nightrate] += array_sum($night_early) * ($nightrate/100);
        $premiumA[$dayrate] += array_sum($day_kladd) * ($dayrate/100);
        $premiumA[$nightrate] += array_sum($night_late) * ($nightrate/100);
        if (!isset($premium_minutesA[$nightrate])) { $premium_minutesA[$nightrate] = 0; }
        if (!isset($premium_minutesA[$dayrate])) { $premium_minutesA[$dayrate] = 0; }
        $premium_minutesA[$nightrate] += array_sum($night_early) + array_sum($night_late);
        if ($showovertimecalc)
        {
          if ($dayrate > 100 && array_sum($day_kladd) > 0) # HERE
          {
            #$showoct .= '<br>'.$weekday_textA[$weekday].': Maj '.($dayrate-100).'% '.array_sum($day_kladd).' min';
            $buffer = str_replace('##'.date_format($date, 'd').'_maj'.($dayrate-100).'##', showtimeworked(array_sum($day_kladd)), $buffer);
          }
          if ((array_sum($night_early) + array_sum($night_late)) > 0)
          {
            #$showoct .= '<br>'.$weekday_textA[$weekday].': Maj '.($nightrate-100).'% '.(array_sum($night_early)+array_sum($night_late)).' min';
            $buffer = str_replace('##'.date_format($date, 'd').'_maj'.($nightrate-100).'##', showtimeworked(array_sum($night_early)+array_sum($night_late)), $buffer);
          }
        }
        $premium_minutesA[$dayrate] += array_sum($day_kladd);
      }
      if ($overtime == 1 && substr($date_compare,5,2) == $month) # only if week ends within month
      {
        $dayrate = 125; #  max 7 hours of this rate
        $dayrate_extra = 150; # after 7 extra hours (for 169 base)
        $nightrate = 175;
        if ($weekday == 7) { $dayrate = 165; $nightrate = 200; }
        if ($d_holidayA[$weekday]) { $dayrate = 265; $nightrate = 300; } # TODO verify this with accountant/expert
        $night_early = array_slice($d_overtimeA[$weekday], 0, 360);
        $day_kladd = array_slice($d_overtimeA[$weekday], 360, 840);
        $night_late = array_slice($d_overtimeA[$weekday], 1200, 240);
        if (!isset($overtimeA[$nightrate])) { $overtimeA[$nightrate] = 0; }
        if (!isset($overtimeA[$dayrate])) { $overtimeA[$dayrate] = 0; }
        $overtimeA[$nightrate] += array_sum($night_early) * ($nightrate/100);
        $overtimeA[$dayrate] += array_sum($day_kladd) * ($dayrate/100);
        $overtimeA[$nightrate] += array_sum($night_late) * ($nightrate/100);
        if (!isset($overtime_minutesA[$nightrate])) { $overtime_minutesA[$nightrate] = 0; }
        if (!isset($overtime_minutesA[$dayrate])) { $overtime_minutesA[$dayrate] = 0; }
        $overtime_minutesA[$nightrate] += array_sum($night_early) + array_sum($night_late);
        $overtime_minutesA[$dayrate] += array_sum($day_kladd);
        $dayrate_extra_count += array_sum($day_kladd);
        if ($showovertimecalc)
        {
          if (array_sum($day_kladd) > 0)
          {
            #$showoct .= '<br>'.$weekday_textA[$weekday].': Sup '.($dayrate-100).'% '.array_sum($day_kladd).' min';
            $buffer = str_replace('##'.date_format($date, 'd').'_sup'.($dayrate-100).'##', showtimeworked(array_sum($day_kladd)), $buffer);
          }
          if ((array_sum($night_early) + array_sum($night_late)) > 0)
          {
            #$showoct .= '<br>'.$weekday_textA[$weekday].': Maj '.($nightrate-100).'% '.(array_sum($night_early)+array_sum($night_late)).' min';
            $buffer = str_replace('##'.date_format($date, 'd').'_sup'.($nightrate-100).'##', showtimeworked(array_sum($night_early)+array_sum($night_late)), $buffer);
          }
        }
      }
      $running_total = $new_running_total + $d_nonworkedA[$weekday]; # adding this at the end, absences counts AFTER hours worked for overtime
      #$buffer .= '<br>end of day ',$weekday,' total time=',$running_total;
    }
    if ($dayrate_extra_count > 480) # 47ime heure comprise
    {
      $dayrate_extra_count -= 480; #$buffer .= '<br>(125)before='.$overtime_minutesA[125];
      foreach ($overtime_minutesA as $temp_rate => $temp_minutes) # rates already over 125 are not moved
      {
        if ($temp_rate > 125) { $dayrate_extra_count -= $temp_minutes; }
      }
      if ($dayrate_extra_count < 0) { $dayrate_extra_count = 0; }
      if ($dayrate_extra_count > 0)
      {
        $overtime_minutesA[125] -= $dayrate_extra_count; #$buffer .= '<br>(125)after='.$overtime_minutesA[125]; # hardcode normal dayrate overtime
        $overtime_minutesA[$dayrate_extra] = $dayrate_extra_count;
        if ($showovertimecalc)
        {
          #$showoct .= '<br>Moving '.$dayrate_extra_count.' min to 150';
          $showoct .= 'Au-delà de la 47ème heure: '.$dayrate_extra_count.' min bougés de 25% à 50%';
        }
      }
    }
    # save summary info
    # array_sum($premiumA)+array_sum($overtimeA) as minutes to pay
    $em_minutes_to_pay += array_sum($premiumA)+array_sum($overtimeA);
    # $premium_minutesA[others] as variable
    foreach ($premium_minutesA as $rate => $value)
    {
      if ($rate != 100 && $value > 0)
      {
        if (!isset($em[0][$rate])) { $em[0][$rate] = 0; }
        $em[0][$rate] += $value;
      }
    }
    # $overtime_minutesA[] as variable
    foreach ($overtime_minutesA as $rate => $value)
    {
      if ($value > 0)
      {
        if (!isset($em[1][$rate])) { $em[1][$rate] = 0; }
        $em[1][$rate] += $value;
      }
    }
    $buffer .= '<td>';
    if ($showovertimecalc)
    {
      $buffer .= '<td align=right colspan=9>';
      $buffer .= $showoct; $showoct = '';
      /*
      foreach ($premium_minutesA as $kladd => $kladd2)
      {
        if ($kladd2 > 0) { $buffer .= '<br>premium'.$kladd.' ',$kladd2; }
      }
      foreach ($overtime_minutesA as $kladd => $kladd2)
      {
        if ($kladd2 > 0) { $buffer .= '<br>overtime'.$kladd.' ',$kladd2; }
      }
      */
      #$buffer .= '<br>total min= ',array_sum($premium_minutesA)+array_sum($overtime_minutesA);
      #$buffer .= '<br>total min à payer= ',array_sum($premiumA)+array_sum($overtimeA);
    }
    ###
    
    $buffer .= '<td>';
    if ($lockme) { $buffer .= '<td><td><td><td>'; }
    if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser']) { $buffer .= '<td>'; }
  }
  
}

if ($totalshown == 0) # copy from above!
{
  $buffer .= '<tr><td class="fixed"><b>Total mois';
  for ($y=0; $y<6; $y++) { $buffer .= '<td>'; }
  $buffer .= '<td align=right><b>' . showtimeworked($timeworked_monthly);
  for ($y=0; $y<6; $y++) { $buffer .= '<td>'; }
  $buffer .= '<td align=right><b>' . showtimeworked($timeworked_monthly2);
  $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[1]);
  $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[2]);
  $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[3]);
  $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[4]);
  $buffer .= '<td align=right><b>' . showtimeworked($nonworkedA[5]);
  $buffer .= '<td align=right><b>'. showtimeworked($timeworked_monthly3);
  $buffer .= '<td>';
  if ($showovertimecalc) { $buffer .= '<td colspan=9>'; }
  $buffer .= '<td>';
  if ($_SESSION['ds_ismanager'] || $_SESSION['ds_ishrsuperuser']) { $buffer .= '<td>'; }
}

$buffer .= '<input type=hidden name="report" value="badge_employeemonth">
<input type=hidden name="update" value="1">
<input type=hidden name="year" value="'.$year.'">
<input type=hidden name="month" value="'.$month.'">
<input type=hidden name="employeeid" value="'.$employeeid.'">
</table></div>';
if ($lockme == 0) { $buffer .= '<center><input type="submit" value="'.d_trad('validate').'"></center>'; }
$buffer .= '</form>';

### save $em_minutes_worked $em_minutes_to_pay $em (array)
$query = 'select employee_monthid from employee_month where employeeid=? and month=? and year=?';
$query_prm = array($employeeid,$month,$year);
require('inc/doquery.php');
if ($num_results)
{
  $employee_monthid = $query_result[0]['employee_monthid'];
  $query = 'update employee_month set minutes_worked=?,minutes_to_pay=? where employee_monthid=?';
  $query_prm = array($em_minutes_worked,$em_minutes_to_pay,$employee_monthid);
  require('inc/doquery.php');
  $query = 'update employee_month_minutes set minutes=0 where employee_monthid=?';
  $query_prm = array($employee_monthid);
  require('inc/doquery.php');
}
else
{
  $query = 'insert into employee_month (employeeid,month,year,minutes_worked,minutes_to_pay) values (?,?,?,?,?)';
  $query_prm = array($employeeid,$month,$year,$em_minutes_worked,$em_minutes_to_pay);
  require('inc/doquery.php');
  $employee_monthid = $query_insert_id;
}
$query = 'update employee_month set nonworked1=?,nonworked2=?,nonworked3=?,nonworked4=?,nonworked5=?,meal_allowance=? where employee_monthid=?';
$query_prm = array($nonworkedA[1],$nonworkedA[2],$nonworkedA[3],$nonworkedA[4],$nonworkedA[5],$total_meal_allowance,$employee_monthid);
require('inc/doquery.php');
for ($x=0; $x <= 1; $x++)
{
  if (isset($em[$x]))
  {
    foreach ($em[$x] as $rate => $minutes)
    {
      $query = 'select employee_month_minutesid from employee_month_minutes where employee_monthid=? and type=? and rate=?';
      $query_prm = array($employee_monthid,$x,$rate);
      require('inc/doquery.php');
      if ($num_results)
      {
        $employee_month_minutesid = $query_result[0]['employee_month_minutesid'];
        $query = 'update employee_month_minutes set minutes=? where employee_month_minutesid=?';
        $query_prm = array($minutes,$employee_month_minutesid);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into employee_month_minutes (employee_monthid,type,rate,minutes) values (?,?,?,?)';
        $query_prm = array($employee_monthid,$x,$rate,$minutes);
        require('inc/doquery.php');
      }
    }
  }
}
###

$buffer = preg_replace('/##[\s\S]+?##/', '', $buffer);
echo $buffer;

?>