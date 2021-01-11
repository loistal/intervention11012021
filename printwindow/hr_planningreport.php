<?php
### keep this
if ($_SESSION['ds_systemaccess'] != 1) { require('logout.php'); exit; }
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

$CSV_DELIMITER = ';';

#Global Variables
$ds_planningteamdayoff = $_SESSION['ds_planningteamdayoff'];
$ds_planningteamdayoffdisplayed = $_SESSION['ds_planningteamdayoffdisplayed'];
$ds_planningteamcommentcolumn = $_SESSION['ds_planningteamcommentcolumn'];
// $ds_ismanagervalidationplanning = $_SESSION['ds_ismanagervalidationplanning'];

#MANAGER ACCESS
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

#nb values a day
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];

#get parameters
#if page reloaded by click son export
$isclickexport = $_GET['isclick'] +0;
if ($isclickexport == 1) 
{
  $period = $_GET['period'];
  $week_year = $_GET['week_year'];
  $month = $_GET['mon'];
  $year_save = $year = $_GET['year'];
  $planningteamvalueid = $_GET['pvid'];
  $employeeid_save = $_GET['empid'];
  $employeeid = $employeeid_save;
  $ismanager = $_GET['ism']+0;
  $myemployeedepartmentid = $_GET['mydid']+0;
  $myemployeesectionid = $_GET['mysid']+0;

  $filename = 'hr_planningreport_dpt' . $myemployeedepartmentid .'_section' . $myemployeesectionid .'_' . date("Y_m_d_H_i_s") . '.csv';
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
  $planningteamvalueid = $_POST['planningteamvalueid'] +0;  
  $employeeid = $employeeid_save = $_POST['employeeid'];
  $ismanager = $_POST['ismanager']+0;
  $myemployeedepartmentid = $_POST['myemployeedepartmentid'] +0;
  $myemployeesectionid = $_POST['myemployeesectionid']+0;  
}

#get parameters
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
    $month_save = $month;
    break;
}

$isuniqueplanningteamvalue = 0;
if ($planningteamvalueid > 0 ) { $isuniqueplanningteamvalue = 1; }

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

$title = d_trad('report');
showtitle($title);

#don't deplace it
session_write_close();

$title = d_trad('report');
showtitle($title);
if ($ourparams == '') { $ourparams = $title;}

#to create xls file
$xlsA = array();

#are there paid leave in report
$ispaidleaveinreport = 0;

?>

<section id="share">
  <?php
  if ($isclickexport == 1) 
  {
    echo '<a href="' . $filepath .'" download="' .$filename .'" class="btn btn-success">' . d_trad('download') . '</a>';
  }
  else
  {
    echo '<a href="printwindow.php?report=hr_planningreport&isclick=1&period=' . $period . '&week_year=' . $week_year . '&mon=' . $month . '&year=' . $year . '&pvid=' . $planningteamvalueid . '&empid=' . $employeeid_save . '&ism=' .$ismanager . '&mydid=' . $myemployeedepartmentid . '&mysid=' . $myemployeesectionid . '" class="btn btn-success">' . d_trad('export') .'</a>';
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

              #Title
              $planningteamvalueidA = array(); $c=0;
              #if we've chosen a planningteamvalue
              if( $isuniqueplanningteamvalue == 1)
              {
                $planningteamvalueidA[$c] = $planningteamvalueid;  
                $planningsymbol = $planningteamvalue_symbolA[$planningteamvalueid];                
                echo '<td class="title3 text-initial">' . $planningsymbol . '</td>';  
                if ($planningteamvalue_ispaidleaveA[$planningteamvalueid] == 1) { $ispaidleaveinreport = 1;}                
                array_push($xlsA, iconv('UTF-8', 'ISO-8859-15',$planningsymbol));
              }
              else
              {
                foreach($planningteamvalueA as $ptvalueid=>$ptvalue)
                {
                  $planningsymbol = $planningteamvalue_symbolA[$ptvalueid];                    
                  echo '<td class="title3 text-initial">' . $planningsymbol . '</td>';    
                  array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$planningsymbol));
                  $planningteamvalueidA[$c] = $ptvalueid;
                  if ($planningteamvalue_ispaidleaveA[$ptvalueid] == 1) { $ispaidleaveinreport = 1;}
                  $c++;
                }
              }
              $nbcol = count($planningteamvalueidA);

              #no total if planningteamvalue chosen
              if( $isuniqueplanningteamvalue == 0)
              {
                echo '<td class="title3 text-initial">' . d_trad('totalpresence') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalpresence')));
                echo '<td class="title3 text-initial">' . d_trad('totalabsence') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalabsence')));
                // if ($ds_ismanagervalidationplanning == 1)
                // {
                  echo '<td class="title3 text-initial">' . d_trad('totalnotvalidated') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalnotvalidated')));
                // }
              }
              // if ( $ispaidleaveinreport == 1)
              // {
                // echo '<td class="title3 text-initial">' . d_trad('paidleavebalance') . '</td>'; array_push($xlsA,d_trad('paidleavebalance'));               
              // }
              echo '<td class="title3 text-initial">' . d_trad('overtime') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('overtime')));  
              echo '<td class="title3 text-initial">' . d_trad('totalovertime') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('totalovertime'))); 
               
              if ( $ds_planningteamcommentcolumn == 1 )
              {
                echo '<td class="title3 text-initial">' . d_trad('comment') . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('comment')));                                  
              }
  
              if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA, $CSV_DELIMITER ); }

$totalA = array();
$grandtotalA = array();
for($c=0;$c<$nbcol;$c++)
{
  $totalA[$c] = 0;
  $grandtotalA[$c] = 0;
}
$grandtotalpresence = 0; $grandtotalabsence = 0; $grandtotalrest = 0; 
$smalltotalovertime = 0; $grandtotalovertime = 0;
$grandtotalnotvalidated = 0;$grandtotalpaidleavebalance = 0;
$verygrandtotalpresence =0;$verygrandtotalabsence =0;$verygrandtotalrest =0;$verygrandtotalpaidleavebalance = 0;

# DISPLAY RESULTS
#for each employee = each line
$ismanager_prev = 0;
$nbtotal = 0;

for($e=0;$e<$nbemployees;$e++)
{
  $employeeid = $employee_todisplayA[$e]['employeeid'];
  $employeename = $employeesortedbyteamA[$employeeid];
  $ismanager = $employeesortedbyteam_ismanagerA[$employeeid];
  $totalpresence = 0;
  $totalabsence = 0;
  $totalrest = 0;  
  $totalpaidleavedays = 0;  
  $paidleavebalance = 0;  
  
  #for a new line in file
  $xlsA = array();
  
  #display total for each team
  if($e != 0 && (($ismanager_prev == 0 && $ismanager == 1)))
  {
    echo '<tr>';   
    echo '<td><b>' . d_trad('teamtotal') . '</b></td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('teamtotal')));    

    for($c=0;$c<$nbcol;$c++)
    {
      echo '<td><b>' . $totalA[$c] . '</b></td>'; array_push($xlsA,$totalA[$c]);        
      $totalA[$c] = 0;
    }
    #no total if planningteamvalue chosen
    if( $isuniqueplanningteamvalue == 0)
    {
      echo '<td><b>' . $grandtotalpresence . '</b></td>'; array_push($xlsA,$grandtotalpresence);  
      echo '<td><b>' . $grandtotalabsence . '</b></td>'; array_push($xlsA,$grandtotalabsence); 
      // if ($ds_ismanagervalidationplanning == 1)
      // {
        echo '<td><b>' . $grandtotalnotvalidated . '</b></td>'; array_push($xlsA,$grandtotalnotvalidated);
      // }
    }
    // if ( $ispaidleaveinreport == 1)
    // {
      // echo '<td><b>' . $grandtotalpaidleavebalance . '</b></td>'; array_push($xlsA,$grandtotalpaidleavebalance);      
    // }
    echo '<td><b>' . d_displayovertime($smalltotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($smalltotalovertime));
    echo '<td><b>' . d_displayovertime($grandtotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($grandtotalovertime));
    echo '</tr>';
    
    if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA ,$CSV_DELIMITER); }
    $xlsA = array();
    
    #reinitialize totals by team
    $grandtotalpresence = $grandtotalabsence = $grandtotalnotvalidated = $smalltotalovertime = $grandtotalovertime = $grandtotalpaidleavebalance = 0;
    
    $nbtotal ++;
  }
    
  echo d_tr();
  if($nbemployees > 1)
  {      
    echo '<td>';
    if ($ismanager) { echo '<b>'; }
    if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $employeeid . '" target=_blank>' .  $employeename . '</a>';}
    else { echo $employeename; } 
    echo '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$employeename));
  }
  
  for($c=0;$c<$nbcol;$c++)
  {
    $nbdaysorhalfdays = 0;
    $ptvalueid = $planningteamvalueidA[$c];
    $nbpaidleavedays = 0;     
 

    for($pv=1;$pv<=$ds_planningteamnbvalues;$pv++)
    {  
      //d_debug('pv',$pv);    
      #if manager validates planningdate
      // if ($ds_ismanagervalidationplanning == 1)
      // {    
        $query = 'select * from planningteamvalidation where validated=1 and deleted=0 and employeeid=? and planningdate>=? and planningdate<=?';
        switch ($pv)
        { 
          case 1:
            $query .= ' and planningteamvalueid1 = ?';   
            break;
          case 2:
            $query .= ' and planningteamvalueid2 = ?';   
            break;
          case 3:
            $query .= ' and planningteamvalueid3 = ?';   
            break;  
        }
        $query_prm = array($employeeid,$reportstart,$reportstop,$ptvalueid);  
        require('inc/doquery.php');          
        $nbdaysorhalfdays += $num_results;   
        //d_debug('nbdaysorhalfdays['.$c.']['.$pv.']: ' .$num_results .' ',$nbdaysorhalfdays);
      // }
 
      /*else
      {
        $hr_statetoexcludeA = array('0','2','9');
        require('reportwindow/hr_selectplanningteam.php');
      }*/
    }

    #if there is 1 value a day: we display it without change
    #if there are 2 values a day: we divide by 2 the count(*)
    #if there are 3 values a day: we count one value off so 2 1/2 day by day as well
    if ($ds_planningteamnbvalues >= 2)
    {
      $nbdaysorhalfdays = $nbdaysorhalfdays / 2;
    }
    //d_debug('nbdaysorhalfdays',$nbdaysorhalfdays);      
      
    #to calculate paid leave balance
    if ($planningteamvalue_ispaidleaveA[$ptvalueid] == 1)
    {
      $previousmonth = $month - 1;  
      #from begining of year to first day of current month (not included)
      $start = d_getfirstdayofmonth(1,$year);
      $stop = d_getfirstdayofmonth($month,$year);
      
      for($pv=1;$pv<=$ds_planningteamnbvalues;$pv++)
      {  
        $query = 'select * from planningteamvalidation pv where pv.validated=1 and pv.deleted=0 and pv.employeeid=? and pv.planningdate>=? and pv.planningdate<?';
        switch ($pv)
        { 
          case 1:
            $query .= ' and planningteamvalueid1 = ?';   
            break;
          case 2:
            $query .= ' and planningteamvalueid2 = ?';   
            break;
          case 3:
            $query .= ' and planningteamvalueid3 = ?';   
            break;  
        }
        $query_prm = array($employeeid,$start,$stop,$ptvalueid);  

        require('inc/doquery.php');          
        $nbpaidleavedays += $num_results;          
      }

      #if there is 1 value a day: we display it without change
      #if there are 2 values a day: we divide by 2 the count(*)
      #if there are 3 values a day: we count one value off so 2 1/2 day by day as well
      if ($ds_planningteamnbvalues >= 2)
      {
        $nbpaidleavedays = $nbpaidleavedays / 2;
      }
        
      $totalpaidleavedays += $nbpaidleavedays;
    }
    $planningteamvaluecolorid = $planningteamvalue_coloridA[$ptvalueid];
    echo '<td style="background-color: #' . $color_codeA[$planningteamvaluecolorid] . '">' . $nbdaysorhalfdays . '</td>'; array_push($xlsA,$nbdaysorhalfdays);    
    $totalA[$c] += 0 + $nbdaysorhalfdays;
    $grandtotalA[$c] += $nbdaysorhalfdays;
    
    #total calculation
    if ($planningteamvalue_absenceA[$ptvalueid] == 1)
    {
      $totalabsence += $nbdaysorhalfdays;
      $grandtotalabsence += $nbdaysorhalfdays;
      $verygrandtotalabsence += $nbdaysorhalfdays;     
    }
    elseif ($planningteamvalue_restA[$ptvalueid] == 1)
    {
      $totalrest += $nbdaysorhalfdays;    
      $grandtotalrest += $nbdaysorhalfdays;      
      $verygrandtotalrest += $nbdaysorhalfdays;      
    }
    elseif($planningteamvalue_presenceA[$ptvalueid] == 1)
    {
      $totalpresence += $nbdaysorhalfdays;    
      $grandtotalpresence += $nbdaysorhalfdays;      
      $verygrandtotalpresence += $nbdaysorhalfdays;      
    }
  }
  
  #overtime and total absence/presence
  switch ($period)
  {
    case $PERIOD_WEEK:
      #total nb of absence days
      #overtime for this week = diff between previous week and this week
      #query for previous week
      $query = 'select totalovertime from totalovertime where employeeid=? and ((week<=? and year=?) or (year<?)) order by year desc,week desc limit 1';
      $query_prm = array($employeeid,$prevweek,$prevyear,$prevyear);
      require('inc/doquery.php');
      $totalovertimeprev = 0;
      if ($num_results > 0) { $totalovertimeprev = $query_result[0]['totalovertime']; }  
      unset($query_result,$num_results);      
      
      #query for this week
      $query = 'select totalovertime from totalovertime where employeeid=? and week=? and year=?';
      $query_prm = array($employeeid,$week,$year);
      require('inc/doquery.php');
      $totalovertime = 0;
      if ($num_results > 0) { $totalovertime = $query_result[0]['totalovertime']; }       
      unset($query_result,$num_results);     
      
      $overtime = 0;
      if ($totalovertime == 0)
      {
        #totalovertime: if no result for this week, check the last record      
        $query = 'select totalovertime from totalovertime where employeeid=? and ((week<=? and year =?) or (year <?)) order by year desc,week desc limit 1';     
        $query_prm = array($employeeid,$week,$year,$year);   
        require('inc/doquery.php');
        if ($num_results > 0) { $totalovertime = $query_result[0]['totalovertime']; }  
      }
      else
      {
        #diff between previous week and this week
        $overtime = $totalovertime - $totalovertimeprev;    
      }
      
      #comments
      if ( $ds_planningteamcommentcolumn == 1 )
      {
        $query = 'select comment from planningteamcomment where employeeid=? and week=? and year=?';
        $query_prm = array($employeeid,$week,$year);
        require('inc/doquery.php');
        $comment = '';
        if ( $query_result[0]['comment'] != NULL )
        {
          $comment .= $query_result[0]['comment'];
        }
      }
      break;
      
    case $PERIOD_MONTH:   
      # overtime is saved every weeks 
      # we look for the first totalovertime before weekstart
      $query = 'select totalovertime from totalovertime where employeeid=? and ((week<? and year=?) or (year<?)) order by year desc,week desc limit 1';       
      $query_prm = array($employeeid,$weekstart,$yearstart,$yearstart);
      require('inc/doquery.php');
      $totalovertimeprev = 0;     
      if ($num_results > 0) { $totalovertimeprev = $query_result[0]['totalovertime']; } 
      unset($query_result,$num_results);
      
      #overtime of the last week of this month
      $query = 'select totalovertime from totalovertime where employeeid=? and ((week<? and year=?) or (year<?)) order by year desc,week desc limit 1';          
      $query_prm = array($employeeid,$weekstop,$yearstop,$yearstop);
      require('inc/doquery.php');
      $totalovertime = 0;
      if ($num_results > 0) { $totalovertime = $query_result[0]['totalovertime']; } 
      unset($query_result,$num_results);      

      #diff between previous month and this month
      #if totalovertime of this month is = 0, we take previous one 
      $overtime  = 0;
      if ($totalovertime == 0)
      {
        $totalovertime = $totalovertimeprev;      
      }
      else
      {
        $overtime = $totalovertime - $totalovertimeprev;
      }    
      
      #comments: concat comment from every week of the month
      if ( $ds_planningteamcommentcolumn == 1 )
      {
        $query = 'select comment from planningteamcomment where employeeid=? and ( week>=? and year=? ) and ( week <=? and year=? )';
        $query_prm = array($employeeid,$weekstart,$year,$weekstop,$year);
        require('inc/doquery.php');
        $comment = '';
        for($co=0;$co<$num_results;$co++)
        {
          if ( $query_result[$co]['comment'] != NULL )
          {
            $comment .= $query_result[$co]['comment'] . '<br>';
          }
        }
      }
      break;
  }
  $smalltotalovertime += $overtime;
  $verysmalltotalovertime += $overtime;
  $grandtotalovertime += $totalovertime;
  $verygrandtotalovertime += $totalovertime;
  
  #no total if planningteamvalue chosen
  if( $isuniqueplanningteamvalue == 0)
  {
    echo '<td>' . $totalpresence . '</td>';array_push($xlsA,$totalpresence); 
    echo '<td>' . $totalabsence . '</td>'; array_push($xlsA,$totalabsence);
    // if ($ds_ismanagervalidationplanning == 1)
    // {  
      $totalnotvalidated = $nbdays - ( $totalpresence + $totalabsence + $totalrest);
      ##if day off are not displayed, we can not validate them
      if ($ds_planningteamdayoffdisplayed == 0)
      {
        $totalnotvalidated -= $nbdaysoff;
      }
      $grandtotalnotvalidated += $totalnotvalidated;
      $verygrandtotalnotvalidated += $totalnotvalidated;
      echo '<td>' . $totalnotvalidated . '</td>';array_push($xlsA,$totalnotvalidated);  
    // }
  }
  // if ( $ispaidleaveinreport == 1)
  // {
    // #to calculate paid leave balance
    // #Employees get 2.5 paid leave days by month: so don't count current month
    // $paidleavebalance = (2.5 * $previousmonth) - $totalpaidleavedays;    
    // $grandtotalpaidleavebalance += $paidleavebalance;
    // $verygrandtotalpaidleavebalance += $paidleavebalance;       
    // echo '<td>' . $paidleavebalance . '</b></td>'; array_push($xlsA,$paidleavebalance);      
  // }  
  echo '<td>' . d_displayovertime($overtime) . '</td>'; array_push($xlsA,d_displayovertimecsv($overtime)); 
  echo '<td>' . d_displayovertime($totalovertime) . '</td>';array_push($xlsA,d_displayovertimecsv($totalovertime));
  if ( $ds_planningteamcommentcolumn == 1 )
  {
    $query = 'select comment from planningteamcomment where employeeid=? and week=? and year=?';
    echo '<td>' . $comment . '</td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',$comment));  
  }
 
  echo '</tr>'; 
  if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA, $CSV_DELIMITER ); }
  $xlsA = array();
  
  $ismanager_prev = $ismanager;
}//for $employeeid

#TOTAL
if($nbemployees > 1)
{   
  #total for last team
  echo '<tr>';   
  echo '<td><b>' . d_trad('teamtotal') . '</b></td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('teamtotal')));

  for($c=0;$c<$nbcol;$c++)
  {
    echo '<td><b>' . $totalA[$c] . '</b></td>'; array_push($xlsA,$totalA[$c]);
    $totalA[$c] = 0;
  }
  if( $isuniqueplanningteamvalue == 0)
  {
    echo '<td><b>' . $grandtotalpresence . '</b></td>'; array_push($xlsA,$grandtotalpresence);
    echo '<td><b>' . $grandtotalabsence . '</b></td>'; array_push($xlsA,$grandtotalabsence);
    // if ( $ds_ismanagervalidationplanning == 1 ) 
    // {    
      echo '<td><b>' . $grandtotalnotvalidated . '</b></td>'; array_push($xlsA,$grandtotalnotvalidated);
    // } 
  }
  // if ( $ispaidleaveinreport == 1)
  // {
    // echo '<td><b>' . $grandtotalpaidleavebalance . '</b></td>'; array_push($xlsA,$grandtotalpaidleavebalance);      
  // }    
  echo '<td><b>' . d_displayovertime($smalltotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($smalltotalovertime));
  echo '<td><b>' . d_displayovertime($grandtotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($grandtotalovertime));
  if ( $ds_planningteamcommentcolumn == 1 ) 
  {
    echo '<td><b>&nbsp;</b></td>'; array_push($xlsA,'');
  }
  echo '</tr>';
  if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA ,$CSV_DELIMITER); }
  $xlsA = array();  
  
  #reinitialize totals by team
  $grandtotalpresence = $grandtotalabsence = $grandtotalnotvalidated = $smalltotalovertime = $grandtotalovertime = $grandtotalpaidleavebalance = 0;
  
  if($nbtotal > 1)
  {
    echo '<tr>';   
    echo '<td><b>' . d_trad('grandtotal') . '</b></td>'; array_push($xlsA,iconv('UTF-8', 'ISO-8859-15',d_trad('grandtotal')));

    for($c=0;$c<$nbcol;$c++)
    {
      echo '<td><b>' . $grandtotalA[$c] . '</b></td>'; array_push($xlsA,$grandtotalA[$c]);
    }
    #no total if planningteamvalue chosen
    if( $isuniqueplanningteamvalue == 0)
    {    
      echo '<td><b>' . $verygrandtotalpresence . '</b></td>'; array_push($xlsA,$verygrandtotalpresence);
      echo '<td><b>' . $verygrandtotalabsence . '</b></td>'; array_push($xlsA,$verygrandtotalabsence);
      // if ($ds_ismanagervalidationplanning == 1)
      // {
        echo '<td><b>' . $verygrandtotalnotvalidated . '</b></td>'; array_push($xlsA,$verygrandtotalnotvalidated);
      // }
    }
    // if ( $ispaidleaveinreport == 1)
    // {
      // echo '<td><b>' . $verygrandtotalpaidleavebalance . '</b></td>'; array_push($xlsA,$verygrandtotalpaidleavebalance);      
    // }      
    echo '<td><b>' . d_displayovertime($verysmalltotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($verysmalltotalovertime));
    echo '<td><b>' . d_displayovertime($verygrandtotalovertime) . '</b></td>'; array_push($xlsA,d_displayovertimecsv($verygrandtotalovertime));
    if ( $ds_planningteamcommentcolumn == 1 ) 
    {
      echo '<td><b>&nbsp;</b></td>'; array_push($xlsA,'');
    }
    echo '</tr>';
    if (($isclickexport == 1) && $file) { fputcsv ( $file , $xlsA, $CSV_DELIMITER ); }
    $xlsA = array();    
  }
}



if (($isclickexport == 1) && $file) { fclose($file);}
?>
          </tbody>
        </table>

</div>
</div>
</body>
</html>
</table>
