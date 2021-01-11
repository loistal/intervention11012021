<?php
require('inc/func_planning.php');
if (!isset($employeesortedbyteamA)) {require ('preload/employeesortedbyteam.php');}
unset($planningteamvalueA);$hr_orderby_absence = 1;require ('preload/planningteamvalue.php');
if(!isset($colorA)){require ('preload/color.php');}?>

<link rel="stylesheet" href="printwindow/hr_report.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<link rel="stylesheet" href="declaration/print.css">

<?php
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
$OVERTIME_FIRST_START_HOUR = 40;
$OVERTIME_FIRST_START_HOUR_MINUTES = 40 * 60;
$OVERTIME_SECOND_START_HOUR = 47;
$OVERTIME_SECOND_START_HOUR_MINUTES = 47 * 60;


#Global Variables
$ds_planningteamdayoff = $_SESSION['ds_planningteamdayoff'];
$ds_planningteamdayoffdisplayed = $_SESSION['ds_planningteamdayoffdisplayed'];
$ds_planningteamcommentcolumn = $_SESSION['ds_planningteamcommentcolumn'];
// $ds_ismanagervalidationplanning = $_SESSION['ds_ismanagervalidationplanning'];
$ds_isbadgemanualentryaccess = $_SESSION['ds_isbadgemanualentryaccess'];  
$ds_numdailylogs = $_SESSION['ds_defaultnumdailylogs'];

#VALUES = COLUMNS FOR EACH DAY (ex: AM/PM/Night)
$ds_planningteamnbvalues = $_SESSION['ds_planningteamnbvalues'];

#MANAGER ACCESS
$ds_systemaccess = $_SESSION['ds_systemaccess']+0;
$ds_myemployeeid = $_SESSION['ds_myemployeeid']+0;
$ds_ishrsuperuser = $_SESSION['ds_ishrsuperuser']+0;

#get parameters
$period = $_POST['period'];
switch($period)
{
  case $PERIOD_WEEK:
    #week_year by post => must separate week and year
    $week_year = $_POST['week_year'];
    $pos_ = mb_strpos($week_year,'_');  
    $week = mb_substr($week_year,0,$pos_);  
    $year = mb_substr($week_year,$pos_+1);  
    $week_save = $week;
    $year_save = $year;      
    break;
  case $PERIOD_MONTH:  
    $month_save = $month = $_POST['month'];
    $year_save = $year = $_POST['year'];
    break;
}
$employeeid = $employeeid_save = $_POST['employeeid'];
$ismanager = $_POST['ismanager']+0;
$myemployeedepartmentid = $_POST['myemployeedepartmentid'] +0;
$myemployeesectionid = $_POST['myemployeesectionid']+0;

require('hr/chooseemployeewithteams.php');

#display params
$employeeidempty = 1;

$nbdays = 0;
switch($period)
{
  case $PERIOD_WEEK:
    #this calculation handle year change
    $reportstart = d_getmonday($week,$year);
    $reportstop = d_getmonday($week+1,$year);  
    $ourparams .= '<p>' . d_trad('weekparam:',array($week,d_getmonday_todisplay($week,$year),d_getsunday_todisplay($week,$year))). '</p>';   
    
    $nbdays = 7;
    $nbdaysoff = 0;
    if ($ds_planningteamdayoff > 0)
    {
      $nbdaysoff = 1;
    }   
    
    break;
  /*case $PERIOD_MONTH:
    $month_display = d_trad('month' . $month);
    #this calculation handle year change    
    $reportstart = d_getfirstdayofmonth($month,$year);
    $reportstop = d_getfirstdayofmonth($month+1,$year);   
    $ourparams .= '<p>' . d_trad('monthyearparam',array($month_display,$year)). '</p>';   

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
    break;*/
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

$title = d_trad('summary');
showtitle($title);

#don't deplace it
session_write_close();?>

<section id="share">
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
          <?php echo $title; ?>
        </h1>
      </div>
      <div class="col-xs-offset-1 col-xs-15 text-center document-title">
        <p>
          <strong>
            <?php echo $ourparams; ?>
          </strong>
        </p>
      </div>
    </div>                
    <div class="row">
      <div class="col-xs-12">
        <table class="table-bordered full-width-table">
          <thead>
						<?php
						#Title first line
						if($nbemployees > 1)
						{ 
							echo '<th></th>';
							$numdailylogs = $ds_numdailylogs;
						}
						else
						{
							#get the number of daily checking for this category of employee
							$query = 'select ec.numdailylogs from employeecategory ec,employee e where ec.deleted=0 and ec.employeecategoryid = e.employeecategoryid and e.employeeid=? and e.deleted=0';
							$query_prm = array($employeeid);
							require('inc/doquery.php');
							$numdailylogs = $_SESSION['ds_defaultnumdailylogs'];
							if ($num_results > 0)
							{
								$numdailylogs = $query_result[0]['numdailylogs'];
							}
						}
						for ($d=1;$d<=7;$d++)
						{
							$dateday = d_getdateadddays($d-1,$reportstart);
							#display without year
							$datedaydisplay = datefix2($dateday);
							$datedaydisplay = mb_substr($datedaydisplay,0,strlen($datedaydisplay)-4);
							echo '<th colspan=' . $numdailylogs . '>'; 
							if($nbemployees > 1) { echo d_trad('shortdayofweek' . $d);}
							else { echo d_trad('dayofweek' . $d);}
							echo '&nbsp;' . $datedaydisplay . '</th>';
						}
						echo '</thead>';

						echo '<tbody>'; 
		
						# DISPLAY RESULTS
						#for each employee = each line
						$ismanager_prev = 0;
						$nbtotal = 0;

						for($e=0;$e<$nbemployees;$e++)
						{
							$eid = $employee_todisplayA[$e]['employeeid'];
							$ename = $employeesortedbyteamA[$eid];
							$eismanager = $employeesortedbyteam_ismanagerA[$eid];
							$badgeuserid_temp = $employeesortedbyteam_badgenumberA[$eid];  
							$totalhours = 0;
							$numerrors = 0;
								
							if ($badgeuserid_temp == 0)
							{
                /*
								//echo '<p class=alert>' . d_trad('pleasefillbadgenumberforemployee',$employeename_temp) . '</p>';
								echo '<p class=alert>' . 'Veuillez saisir le numéro de badge pour l\'employé: ';
								if ($ds_systemaccess) 
								{ 
									echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $eid . '" target=_blank>' . $ename . '</a>';
								}
								else
								{
									echo $badgeemployeename;
								}
								echo '</p>';
                */
							}
							else
							{       
								echo d_tr();
								if($nbemployees > 1)
								{
									echo '<td>';
									if ($ismanager) { echo '<b>'; }
									if ($ds_systemaccess) { echo '<a href="hr.php?hrmenu=modemployee&step=' . $STEP_FORM_MODIFYEMPLOYEE . '&id=' . $eid . '" target=_blank>' .  $ename . '</a>';}
									else { echo $ename; } 
									echo '</td>';
									
									#get the number of daily checking for this category of employee
									$query = 'select ec.numdailylogs from employeecategory ec,employee e where ec.deleted=0 and ec.employeecategoryid = e.employeecategoryid and e.employeeid=? and e.deleted=0';
									$query_prm = array($eid);
									require('inc/doquery.php');
									$numdailylogs = $ds_numdailylogs;
									if ($num_results > 0)
									{
										$numdailylogs = $query_result[0]['numdailylogs'];
									}
								}

								#get badgelog for each day of period 
								for($d=1;$d<=$nbdays;$d++)
								{
									$date = d_getdateadddays($d-1,$reportstart) ;
									$day = mb_substr($date,8,2);
									$month = mb_substr($date,5,2);
									$year = mb_substr($date,0,4);
									$dayofweek = d_getdayofweek($date);
									
									#to know if the day must be worked or not
									require('hr/badge_hastobeworked.php');

									$query = 'select * from badgelog where deleted=0 and badgeuserid=? and badgedate=? order by badgetime';
									$query_prm = array($badgeuserid_temp,$date);
									require('inc/doquery.php');
									$numlogssaved = $num_results;
									
									$href = 'hr.php?hrmenu=badgemanualentry&badgeemployeeid=' .$eid . '&badgeuserid=' . $badgeuserid_temp . '&badgedate=' . $date; 

									for($c=0;$c<$numdailylogs;$c++)
									{    
										echo '<td align=center>';
										if ($ds_isbadgemanualentryaccess == 1) { echo '<a href="' . $href . '" target=_blank>'; }
										#not enough records
										if ( $c >= $numlogssaved ) 
										{
                      /*
											#TODO if clients ask it: display errors for 1/2 days
											if (!$isbankholiday && $hastobeworked > 0)
											{
												echo '<img src="pics/exclamation.png" name="alert">';
											}
											echo '--:--';
                      */
										}
										else
										{
											$badgetimedisplay = $query_result[$c]['badgetime'];
											if (!isset($badgetimedisplay)) 
											{ 
                        /*
												if (!$isbankholiday && $hastobeworked > 0)
												{
													echo '<img src="pics/exclamation.png" name="alert">'; 
												}
												echo '--:--';
                        */
											}
											else
											{
												echo d_displaytime($badgetimedisplay);
											}
										}
										#if there are too many records put ...after the last record
										if ( ($c == ($numdailylogs -1)) && ($numlogssaved > $numdailylogs)) { echo '<img src="pics/exclamation.png" name="alert">...';} 
										if ($ds_isbadgemanualentryaccess == 1) { echo '</a>'; }      
										echo '</td>';
										#to save badgetime before modification
									}

								}//for days
							}
						}//for $employeeid?>
					</tbody>
				</table> 
			</div>
		</div>
	</div>
</div>