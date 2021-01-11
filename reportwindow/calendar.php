<style>
.trplanningcolor1{
  background-color: white;
}
.trplanningcolor2{
  background-color: #BDBDBD;
}
table.planning {
  background-color: white;
  border: 1px solid #696969;
  border-spacing: 0px;
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
.planningevents{
  background-color: #CEF6CE;
  border-left: 1px solid #696969;
}
.planningtitle{
  background-color: #E0ECF8;
  border: 1px solid #696969;  
  font-weight: bold;
}

.planningtitleminutes{
  background-color: #E0ECF8;
  border-top: 1px dotted #696969;  
  border-bottom: 1px dotted #696969;  
  border-left: 1px solid #696969;  
  border-right: 1px solid #696969;  
}
.planninghours{
  border: 1px solid #696969;  
}
.planningminutes{
  border: 1px dotted #696969;  
}
.planningcolor1{
  background-color: #F8ECE0;
  border: 1px solid #696969; 
  text-align: center;
}
.planningcolor2{
  background-color: #F7F8E0;
  border: 1px solid #696969;  
  text-align: center;
}
.planningcolor3{
  background-color: #ECF8E0;
  border: 1px solid #696969;    
  text-align: center;  
}
.planningcolor4{
  background-color: #E0E0F8;
  border: 1px solid #696969; 
  text-align: center;
}
.planningcolor5{
  background-color: #ECE0F8;
  border: 1px solid #696969;    
  text-align: center;  
}
.planningcolor6{
  background-color: #F8E0F7;
  border: 1px solid #696969;  
  text-align: center;
}
.planningcolor7{
  background-color: #F8E0EC;
  border: 1px solid #696969;    
  text-align: center;  
}
.planningcolor8{
  background-color: #F8E0E6;
  border: 1px solid #696969;    
  text-align: center;  
}
.planningcolor9{
  background-color: #F8E0E0;
  border: 1px solid #696969;  
  text-align: center;
}

</style>

<?php

require('inc/func_planning.php');

$DEBUG = 0;
$MAX_RESULTS = 100;
$PERIOD_DATE = 0;
$PERIOD_WEEK = 1;
$PERIOD_MONTH = 2;
$COLBYDAYWHENDATE = 10;

$ds_adminaccess = $_SESSION['ds_adminaccess'];
$ds_myemployeeid = $_SESSION['ds_myemployeeid'];
$ds_curdate = $_SESSION['ds_curdate'];

$currentday = mb_substr($ds_curdate,8,2);
$currentmonth = mb_substr($ds_curdate,5,2);
$currentyear = mb_substr($ds_curdate,0,4);
$currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear); # TODO 2038
$currentweek = date('W',$currenttimestamp);
$currentmonth = date('n',$currenttimestamp);
if(startswith($currentweek,'0')){$currentweek = mb_substr($currentweek,1,1);}
if (!isset($simple_form)) { $simple_form = 0; }

if (isset($simple_form) && $simple_form)
{
  if (!isset($_SESSION['ds_current_calendar_week'])) { $_SESSION['ds_current_calendar_week'] = $currentweek; }
  $_SESSION['ds_current_calendar_week'] += (int) $_GET['weekmod'];
  $currentweek = $_SESSION['ds_current_calendar_week'];
  if ($currentweek < 1) { $_SESSION['ds_current_calendar_week'] = $currentweek = 1;  }
  if ($currentweek > 53) { $_SESSION['ds_current_calendar_week'] = $currentweek = 53;  }
  
  echo '<h2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="sales.php?salesmenu=planning_simple&weekmod=-1">&#8592;</a> ';
  echo 'Semaine '.$currentweek;
  echo ' <a href="sales.php?salesmenu=planning_simple&weekmod=1">&#8594;</a></h2>';
}

$PA['iscalendarform'] = 'int';
require('inc/readpost.php');

if($iscalendarform)
{
  $period = $_POST['period'];
  $datename = 'date';require('inc/datepickerresult.php');
  $week = $_POST['week'];
  $month = $_POST['month'];
  $starttime = $_POST['starthour'];
  $starthour = d_hours($starttime);
  $startminutes = d_minutes($starttime);
  $stoptime = $_POST['stophour'];
  $stophour = d_hours($stoptime);
  $stopminutes = d_minutes($stoptime);
  $employeeid = $_POST['employeeid'];
  $resourceid = $_POST['resourceid'];
  $num_results=0;$client = $_POST['client'];require('inc/findclient.php');$clientnum_results=$num_results;
}
else
{
  $period = $PERIOD_WEEK;
  $week = $currentweek;
  if (isset($_GET['week'])) { $week = (int) $_GET['week']; }
  $employeeid = $ds_myemployeeid;
  if (isset($calendar_clientid) && $calendar_clientid > 0) # for showclient
  {
    $employeeid = -1;
    if (!isset($_POST['week']) || (int) $_POST['week'] > 1)
    {
      $currenttimestamp = mktime(0,0,0,$currentmonth,$currentday,$currentyear);
      $week = date('W',$currenttimestamp)+0;
    }
    else
    {
      $week = (int) $_POST['week'];
    }
  }
}

session_write_close();

$nbdays = 1;
$dateA = array();
$ourparams = '';
switch($period)
{
  case 0:
    $dateA[0] = $date;
    break;
  case 1:
		$year = $currentyear;
		#if($week < $currentweek){$year = $currentyear +1;} 2020 03 24

    $nbdays = 7; 
    for($day=0;$day<$nbdays;$day++)
    {
      $dateA[$day] = d_getday($day+1,$week,$year);  
    }
    $ourparams .= '<p>' . d_trad('weekparam:',array($week,d_getmonday_todisplay($week,$year),d_getsunday_todisplay($week,$year))) . '</p>';    
    break;
  case 2:
    $month_display = d_trad('month' . $month);
    $ourparams .= '<p>' . d_trad('monthparam:',$month_display). '</p>';       
    break;
}
if(!$iscalendarform)
{
  #determine min starthour and max stophour for entire week: query done 2 times for starthour and stophour
  $starthour = 23;
  $startminutes = 59;
  $stophour = 0;
  $stopminutes = 0;

  for($day=0;$day<7;$day++)
  {
    $date = $dateA[$day];
    if(!empty($date))
    {
      $dateday = mb_substr($date,8,2);
      $datemonth = mb_substr($date,5,2);
      $dateyear = mb_substr($date,0,4);
      $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
      $month = date('m',$datetimestamp);
      $dayofweek = date('N',$datetimestamp);
      $week = date('W',$datetimestamp);
      $dateday = date('j',$datetimestamp);
      if($DEBUG){echo "dayofweek=$dayofweek / month = $month /week = $week /day = $dateday<br>";}    
    }      
    
    for($querynum=0;$querynum<2;$querynum++)
    {
      if(!$querynum)
      {
        $query = 'select p.planningtimestart from planning p, planning_employee pe where p.deleted = 0 and p.planningid=pe.planningid and pe.employeeid=? and (p.planningstart <= ? and p.planningstop >= ?) and p.planningtimestart is not null';
      }
      else
      {
        $query = 'select p.planningtimestop from planning p, planning_employee pe where p.deleted = 0 and p.planningid=pe.planningid and pe.employeeid=? and (p.planningstart <= ? and p.planningstop >= ?) and p.planningtimestop is not null';    
      }
      $query_prm = array($employeeid,$date,$date);

      #WHERE
      $query .= ' and (';
        #punctual
        $query .= '(p.periodic = 0 and p.planningdate = ?)';array_push($query_prm,$date);    
        #yearly
        $query .= 'or (p.periodic = 3 and DAY(p.planningdate) = ? and MONTH(p.planningdate) = ?)';array_push($query_prm,$dateday,$month);    
        #weekly
        $query .= ' or (p.periodic = 1 and p.dayofweek = ?';array_push($query_prm,$dayofweek);  
          #every week
          $query .= ' and (';
            $query .= '(p.periodic_spec=0)'; 
            if($week%2 == 1)
            {
              #every odd week 
              $query .= ' or (p.periodic_spec=1)';
            }
            else
            {
              #every even week 
              $query .= ' or (p.periodic_spec=2)';
            }
            if($dateday >=1 && $dateday <=7)
            {
              #every 1st week of month
              $query .= ' or (p.periodic_spec=3)';
            }
            else if($dateday >=8 && $dateday <=14)
            {
              #every 2nd week of month
              $query .= ' or (p.periodic_spec=4)';
            }
            else if($dateday >=15 && $dateday <=21)
            {     
              #every 3rd week of month
              $query .= ' or (p.periodic_spec=5)';
            }
            else if($dateday >=22 && $dateday <=31)
            {  
              #every 4th week of month
              $query .= ' or (p.periodic_spec=6)';  
            }
          $query .= ')';
        $query .= ')';

        #monthly
        $query .= ' or (p.periodic = 2 and DAY(p.planningdate) = ?';array_push($query_prm,$dateday);     
          #every month
          $query .= ' and (';
            $query .= '(p.periodic_spec=0)'; 
            if($month%2 == 1)
            {
              #every odd month
              $query .= ' or (p.periodic_spec=1)';
            }
            else
            {
              #every even month 
              $query .= ' or (p.periodic_spec=2)';
            }
            #every 3 months
            $query .= ' or (p.periodic_spec=3 and ? in (MONTH(p.planningstart) -9, MONTH(p.planningstart) -6,MONTH(p.planningstart) -3,MONTH(p.planningstart),MONTH(p.planningstart) + 3, MONTH(p.planningstart) +6 , MONTH(p.planningstart) + 9))';array_push($query_prm,$month);   
            #every 6 months
            $query .= ' or (p.periodic_spec=4 and ? in (MONTH(p.planningstart) -6, MONTH(p.planningstart), MONTH(p.planningstart) +6 ))';array_push($query_prm,$month);   
          $query .= ')';
        $query .= ')';
      $query .= ')'; 

      #ORDER BY
      if(!$querynum)
      {
        $query .= ' order by p.planningtimestart limit 1';
      }
      else
      {
        $query .= ' order by p.planningtimestop desc limit 1';    
      }

      #results
      require('inc/doquery.php');
      
      if($num_results > 0)
      {
        if(!$querynum)
        {
          $planningtimestart = $query_result[0]['planningtimestart'];
          if(d_hourswithout0($planningtimestart) <= $starthour)
          {
            $starthour = d_hourswithout0($planningtimestart);
            if(d_minuteswithout0($planningtimestart) < $startminutes)
            {          
              $startminutes = d_minuteswithout0($planningtimestart);
            }
          }     
          if($DEBUG){echo 'planningtimestart[' .$day. ']=' . $planningtimestart .'<br>starthour[' .$day. ']=' . $starthour .'/ startminutes = ' . $startminutes . '<br>';}
        }
        else
        {
          $planningtimestop = $query_result[0]['planningtimestop'];           
          if(d_hourswithout0($planningtimestop) >= $stophour)
          {
            $stophour = d_hourswithout0($planningtimestop);
            if(d_minuteswithout0($planningtimestop) > $stopminutes)
            {          
              $stopminutes = d_minuteswithout0($planningtimestop);
            }
          }  
          if($DEBUG){echo 'planningtimestop[' .$day. ']=' . $planningtimestop .'<br>stophour[' .$day. ']=' . $stophour .'/ stopminutes = ' . $stopminutes . '<br>';}
        }
      }//if $num_results > 0
    }//for $querynum
  }//for $day
}
  
#display params
$employeeidempty = 1;
$clientempty = 1;
$clientidempty = 1; if (isset($calendar_clientid) && $calendar_clientid > 0) { $clientidempty = 0; }
$resourceidempty = 1;

if(isset($employeeid) && $employeeid >= 0)
{
	$employeeidempty = 0;
	if(!isset($employeeA)){require('preload/employee.php');} 
	$ourparams .= '<p>'. d_trad('employeeparam',$employeeA[$employeeid]) .'</p>';
}
if(!empty($client))
{
	$clientempty = 0;
	if(!empty($clientid) && $clientid > -1)
	{ 
		$clientidempty = 0;
	  $ourparams .= '<p>'. d_trad('clientparams',array($clientname,$clientid)) .'</p>';
	}
	else 
	{
	  $ourparams .= '<p>'. d_trad('clientparam',$client) .'</p>';  
	}
}
if(!empty($resourceid) && $resourceid > -1)
{ 
	$resourceidempty = 0;
	if(!isset($resourceA)){require('preload/resource.php');} 
	$ourparams .= '<p>'. d_trad('resourceparam',$resourceA[$resourceid]) .'</p><br>';
}
if($iscalendarform)
{
  $title = d_trad('calendar');
  showtitle($title);
  echo '<h2>' . $title . '</h2>';
  echo $ourparams;  
}

/*
echo '<br>clientidempty='.$clientidempty;
echo '<br>employeeidempty='.$employeeidempty;
echo '<br>';*/

$num_resultsA = array();
$query_resultA = array();
$titleA = array();
$colorA = array();
$nbcolA = array();
$noplanningA = array();
$theadA = array();
$planningwithouttimeA = array();
$totallines = 0;
$noresult = 1;
$noresultwithtime = 1;

for($day=0;$day<$nbdays;$day++)
{
  $date = $dateA[$day];
  if(!empty($date))
  {
    $dateday = mb_substr($date,8,2);
    $datemonth = mb_substr($date,5,2);
    $dateyear = mb_substr($date,0,4);
    $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
		$month = date('m',$datetimestamp);
    $dayofweek = date('N',$datetimestamp);
    $week = date('W',$datetimestamp);
    $dateday = date('j',$datetimestamp);
    if($DEBUG){echo "dayofweek=$dayofweek / month = $month /week = $week /day = $dateday<br>";}    
  }  
 
  #SELECT
  $query = 'select p.planningid,p.planningdate,p.planningstart,p.planningstop,p.planningtimestart,p.planningtimestop,p.planningname,p.dayofweek,p.periodic,p.periodic_spec ';
  $query_prm = array();

  if (!$employeeidempty) { $query .= ',concat(IFNULL(concat(e.employeename," "),""),IFNULL(e.employeefirstname,""))';} 
  if (!$clientidempty || !$clientempty) { $query .= ',c.clientname';}
  if(!$resourceidempty) { $query .= ',r.resourcename';}

  #FROM
  $query .= ' from planning p';
  if (!$employeeidempty) { $query .= ',planning_employee pe, employee e'; }
  if (!$clientidempty || !$clientempty) { $query .= ',planning_client pc,client c'; }
  if(!$resourceidempty) { $query .= ',planning_resource pr,resource r'; }

  #WHERE
  $query .= ' where p.deleted = 0';
  if(!$employeeidempty)
  {
      $query .= ' and p.planningid = pe.planningid and pe.employeeid = e.employeeid and pe.employeeid=?';
      array_push($query_prm,$employeeid);
  }
  if(!$clientidempty)
  {
    $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and pc.clientid = ?'; 
    array_push($query_prm,$clientid);
  }
  elseif(!$clientempty)
  {
    $query .= ' and p.planningid = pc.planningid and c.clientid=pc.clientid and c.clientname LIKE ?';    
    array_push($query_prm,'%' . $client . '%');
  }
  if(!$resourceidempty)
  {
    $query .= ' and p.planningid = pr.planningid and pr.resourceid = r.resourceid and pr.resourceid=?'; 
    array_push($query_prm,$resourceid);    
  }
 
  $query .= ' and (p.planningstart <= ? and p.planningstop >= ?)';   
  array_push($query_prm,$date,$date);  

  $query .= ' and (';
    #punctual
    $query .= '(p.periodic = 0 and p.planningdate = ?)';array_push($query_prm,$date);    
    #yearly
    $query .= 'or (p.periodic = 3 and DAY(p.planningdate) = ? and MONTH(p.planningdate) = ?)';array_push($query_prm,$dateday,$month);    
    #weekly
    $query .= ' or (p.periodic = 1 and p.dayofweek = ?';array_push($query_prm,$dayofweek);  
      #every week
      $query .= ' and (';
        $query .= '(p.periodic_spec=0)'; 
        if($week%2 == 1)
        {
          #every odd week 
          $query .= ' or (p.periodic_spec=1)';
        }
        else
        {
          #every even week 
          $query .= ' or (p.periodic_spec=2)';
        }
        if($dateday >=1 && $dateday <=7)
        {
          #every 1st week of month
          $query .= ' or (p.periodic_spec=3)';
        }
        else if($dateday >=8 && $dateday <=14)
        {
          #every 2nd week of month
          $query .= ' or (p.periodic_spec=4)';
        }
        else if($dateday >=15 && $dateday <=21)
        {     
          #every 3rd week of month
          $query .= ' or (p.periodic_spec=5)';
        }
        else if($dateday >=22 && $dateday <=31)
        {  
          #every 4th week of month
          $query .= ' or (p.periodic_spec=6)';  
        }
      $query .= ')';
    $query .= ')';

    #monthly
    $query .= ' or (p.periodic = 2 and DAY(p.planningdate) = ?';array_push($query_prm,$dateday);     
      #every month
      $query .= ' and (';
        $query .= '(p.periodic_spec=0)'; 
        if($month%2 == 1)
        {
          #every odd month
          $query .= ' or (p.periodic_spec=1)';
        }
        else
        {
          #every even month 
          $query .= ' or (p.periodic_spec=2)';
        }
        #every 3 months
        $query .= ' or (p.periodic_spec=3 and ? in (MONTH(p.planningstart) -9, MONTH(p.planningstart) -6,MONTH(p.planningstart) -3,MONTH(p.planningstart),MONTH(p.planningstart) + 3, MONTH(p.planningstart) +6 , MONTH(p.planningstart) + 9))';array_push($query_prm,$month);   
        #every 6 months
        $query .= ' or (p.periodic_spec=4 and ? in (MONTH(p.planningstart) -6, MONTH(p.planningstart), MONTH(p.planningstart) +6 ))';array_push($query_prm,$month);   
      $query .= ')';
    $query .= ')';
  $query .= ')'; 

  #ORDER BY
  $query .= ' order by p.planningtimestart limit '.$MAX_RESULTS;   

  #results
  require('inc/doquery.php');
  $num_resultsA[$day] = $num_results;$query_resultA[$day] = $query_result;unset($num_results,$query_result);
  #$row0 = $query_resultA[$day][0]; ???
  if($DEBUG){$temp = $num_resultsA[$day]; echo "num_resultsA[$day]=$temp";}
  
  $theadA[$day] = '<th class=planningtitle colspan=' .$COLBYDAYWHENDATE. '>' .d_trad('datewithwords' , array(d_trad('dayofweek'.$dayofweek),$dateday,d_trad('month'.$month))) . '</th>';
  
  #Process results to prepare display
  if($num_resultsA[$day] > 0)
  {
    $noresult = 0;
    if($period == $PERIOD_DATE || $period == $PERIOD_WEEK)
    {
      #results whithout time and before  start time
      $i=0;
      $planningwithouttime = '';
      while ($i<$num_resultsA[$day] )
      {
        $planningtimestart = $query_resultA[$day][$i]['planningtimestart'];
        $planningtimestop = $query_resultA[$day][$i]['planningtimestop'];		
        $planningstarthour = d_hours($planningtimestart);
        $planningstartminutes = d_minutes($planningtimestart);    
        $planningid = $query_resultA[$day][$i]['planningid'];    
        $planningname = $query_resultA[$day][$i]['planningname'];
  
        if($DEBUG){echo "starthour=$starthour /startminutes = $startminutes / planningstarthour[$i] = $planningstarthour / planningstartminutes[$i] = $planningstartminutes<br>";  }
      
        if( ($planningtimestart == NULL) || (($planningtimestart != NULL) && (($planningstarthour < $starthour) || ($planningstarthour == $starthour && $planningstartminutes < $startminutes))) )
        {
          if ($simple_form)
          {
            $planningwithouttime .= '<a href=sales.php?salesmenu=planning_simple&modplanningid=';
          }
          elseif ($ds_adminaccess)
          {
            $planningwithouttime .= '<a href=admin.php?adminmenu=planning&modplanningid=';
          }
          else
          {
            $planningwithouttime .= '<a href=reportwindow.php?report=planning_readonly&planningid=';          
          }
          $planningwithouttime .= $planningid;
          if (!$simple_form) { $planningwithouttime .= ' target=_blank'; }
          $planningwithouttime .= '>';
          $planningwithouttime .= $planningname;
					$planningwithouttime .= d_displaytimeinterval($planningtimestart,$planningtimestop,1) . '<br>';
        }
        else
        {
          break;
        }
        $i++;
      } 
      
      if($i < $num_resultsA[$day])
      {
        ##process results in 2tables
        ##titleA[$day][$m][$i] : each title of plannings 
        ##colorA[$day][$m][$i] : color of cells 
        
        $linenb = -1;
        $nbcolA[$day] = 0;
        $color = 1;  
        if($DEBUG){echo "planning[$day][$i] $starthour:$startminutes à $stophour:$stopminutes";  }
        for ($h=$starthour;$h<=$stophour;$h++)
        {          
          $mstart = 0;$mstop = 45;   
          if($h == $starthour) 
          { 
            $mstart = $startminutes; 
            if($h==$stophour) 
            {
              $mstop = $stopminutes;
            } 
          } 
          elseif($h == $stophour) 
          { 
            $mstop = $stopminutes; 
          }          
          for($m=$mstart;$m<=$mstop;$m+=15)
          {    
            $linenb++; #linenumber  
            if($DEBUG){echo "heure $h:$m ligne $linenb<br>";  }            
            $m_next = $m + 15;      
            $no_result =0;
            while($i<$num_resultsA[$day] && !$no_result)
            {
              $planningtimestart = $query_resultA[$day][$i]['planningtimestart'];
              $planningtimestop = $query_resultA[$day][$i]['planningtimestop'];
              $planningstarthour = d_hours($planningtimestart);
              $planningstartminutes = d_minutes($planningtimestart);        
              $planningstophour = d_hours($planningtimestop);
              $planningstopminutes = d_minutes($planningtimestop);    
							if($DEBUG){echo $i;}// planningstarthour[$day][$linenb] = $planningstarthour:$planningstartminutes / planningstophour = $planningstophour:$planningstopminutes<br>"; 
              if($h == $planningstarthour && $m <= $planningstartminutes  && $m_next > $planningstartminutes)
              {
                $noresultwithtime = 0;
								if($DEBUG){echo 'debug' .$h. ':' .$m . '<br>';}// planningstarthour[$i] = $planningstarthour / planningstophour[$i] = $planningstophour<br>";
                ##verify if we can not put this item in another previous empty col
                $col_empty=0;
                while(!empty($colorA[$day][$linenb][$col_empty]) && $col_empty <$COLBYDAYWHENDATE)
                {
                  $col_empty ++;
                }
                ##number of filled col
                if($col_empty+1 > $nbcolA[$day]){$nbcolA[$day] = $col_empty+1;}
                if($col_empty == $COLBYDAYWHENDATE-1)
                {
                  $titleA[$day][$linenb][$col_empty] = d_trad('more');
                }
                else
                {
									$title = '';
                  if ($simple_form)
                  {
                    $title .= '<a href=sales.php?salesmenu=planning_simple&modplanningid=';
                  }
                  elseif ($ds_adminaccess)
                  {
                    $title .= '<a href=admin.php?adminmenu=planning&modplanningid='; 
                  }
                  else
                  {
                    $title .= '<a href=reportwindow.php?report=planning_readonly&planningid=';
                  }
									$title .= $query_resultA[$day][$i]['planningid'];
                  if (!$simple_form) { $title .= ' target=_blank'; }
                  $title .= '>';		
									$title .= $query_resultA[$day][$i]['planningname'];
                  if ($simple_form)
                  {
                    $query = 'select clientname from client,planning_client where planning_client.clientid=client.clientid and linenr=1 and planningid=?';
                    $query_prm = array($query_resultA[$day][$i]['planningid']);
                    require('inc/doquery.php');
                    $title .= '<br>'.d_decode(d_output($query_result[0]['clientname']));
                  }
									$title .= '<br>'.d_displaytimeinterval($planningtimestart,$planningtimestop,1);					
									$titleA[$day][$linenb][$col_empty] = $title ;
                }
                $colorA[$day][$linenb][$col_empty] = $color; 
                $color++; if($color == 10){$color = 1;} 
                if($DEBUG){$temp = $titleA[$day][$linenb][$col_empty];$tempo = $colorA[$day][$linenb][$col_empty];echo "titleA[$day][$linenb][$col_empty] = $temp </a>/ colorA[$day][$linenb][$col_empty] = $tempo<br>";}

                ##go on fill the table with this planning until its end
                $h_plan = $h;$m_plan = $m +15; if($m_plan == 60){$m_plan =0;$h_plan++;}
                $line_plan = $linenb +1;
                while((($h_plan > $starthour && $h_plan < $stophour ) 
                  || ($h_plan == $starthour && $m_plan > $startminutes && (($h_plan == $stophour  && $m_plan <= $stopminutes) || ($h_plan < $stophour)))
                  || ($h_plan > $starthour && $h_plan == $stophour && $m_plan <= $stopminutes ))
                  && (($h_plan > $planningstarthour && $h_plan < $planningstophour ) 
                  || ($h_plan == $planningstarthour && $m_plan > $planningstartminutes && (($h_plan == $planningstophour  && $m_plan <= $planningstopminutes) || ($h_plan < $planningstophour)))
                  || ($h_plan > $planningstarthour && $h_plan == $planningstophour && $m_plan <= $planningstopminutes )))
                {
                  $colorA[$day][$line_plan][$col_empty] = $colorA[$day][($line_plan-1)][$col_empty]; 
                  if($DEBUG){$tempo = $colorA[$day][$line_plan][$col_empty];echo "colorA[$day][$line_plan][$col_empty] = $tempo<br>";}
                  
                  $m_plan +=15; if($m_plan == 60){$m_plan =0;$h_plan++;}
                  $line_plan ++;
                }
                $i++;
              }
              else
              {
                $no_result = 1;
              }
            }#while i
          }#for $m
        }#for $h
        
        #results after stoptime
        while($i<$num_resultsA[$day])
        {
					$planningtimestart = $query_resultA[$day][$i]['planningtimestart'];
					$planningtimestop = $query_resultA[$day][$i]['planningtimestop'];
					$planningstarthour = d_hours($planningtimestart);
					$planningstartminutes = d_minutes($planningtimestart);        
					$planningstophour = d_hours($planningtimestop);
					$planningstopminutes = d_minutes($planningtimestop);    

          if((($planningtimestop != NULL) && (($planningstophour > $stophour) || ($planningstophour == $stophour && $planningstopminutes > $stopminutes))) )
          {
            if ($simple_form)
            {
              $planningwithouttime .= '<a href=sales.php?salesmenu=planning_simple&modplanningid=';
            }
            if ($ds_adminaccess)
            {
              $planningwithouttime .= '<a href=admin.php?adminmenu=planning&modplanningid=';
            }
            else
            {
              $planningwithouttime .= '<a href=reportwindow.php?report=planning_readonly&planningid=';          
            }
            $planningwithouttime .= $planningid;
            if (!$simple_form) { $planningwithouttime .= ' target=_blank'; }
            $planningwithouttime .= '>';
						$planningwithouttime .= $planningname;
						$planningwithouttime .= d_displaytimeinterval($planningtimestart,$planningtimestop,1) . '<br>';
          }
          $i++;
        }#while i
      }#if $i < $num_resultsA[$day]
      else
      {
        $noplanningA[$day] = 1;
				if($DEBUG){echo "noplanningA[$day] = 1";}
      }
      if($planningwithouttime != '') {$planningwithouttimeA[$day] = $planningwithouttime;}      
      if (isset($linenb)) { $totallines = $linenb +1; } #total nb of lines
      //if(!isset($planningwithouttimeA[$day])){ $planningwithouttimeA[$day] = '';}
    }#if period = date/week

    ##debug   
    if($DEBUG){
      $linenb = -1;
      for ($h=$starthour;$h<=$stophour;$h++)
      {
        $mstart = 0;$mstop = 45;   
        if($h == $starthour) 
        { 
          $mstart = $startminutes; 
          if($h==$stophour) 
          {
            $mstop = $stopminutes;
          } 
        } 
        elseif($h == $stophour) 
        { 
          $mstop = $stopminutes; 
        }       
        for($m=$mstart;$m<=$mstop;$m+=15)
        { 
          $linenb++;
          for($col=0;$col<$nbcolA[$day];$col++)
          {
            $temp = $titleA[$day][$linenb][$col];$tempo = $colorA[$day][$linenb][$col];echo "titleA[$day][$linenb][$col]=$temp </a>/ colorA[$linenb][$col] = $tempo<br>";
           }
        }
      }
    }#if debug
    
  }#if results  
}#for $day  
 
#debug
if($DEBUG)
{
  for($day=0;$day<$nbdays;$day++)
  {
    $temp = $num_resultsA[$day];$tempo = $noplanningA[$day];echo "<br>num_resultsA[$day] = $temp / noplanningA[$day] = $tempo";
  }  
}
##display results
##thead + planning not in the table (without time or time before/after)
if(1==1) # was !$noresult
{
  if(!$iscalendarform)
  {
    if (isset($calendar_clientid) && $calendar_clientid > 0)
    {
      #echo '<div class="myblock" style="width: 1200px">';
    }
    else
    {
      echo '<div class="myblock" style="width:90%;margin:auto;">';
    }
  }
  echo '<table class="planning" style="width: 1200px">';
  echo '<thead>';
  if(1==1) # $iscalendarform || (!$iscalendarform && !$noresultwithtime)
  {
    echo '<th class=planningempty>';
    echo '<a href="index.php?week=' . ($week-1) . '">&#8592;</a> ';
    echo 'Semaine '.$week;
    echo ' <a href="index.php?week=' . ($week+1) . '"">&#8594;</a>';
  }

  for($day=0;$day<$nbdays;$day++)
  {
    echo $theadA[$day];
  }
  echo '</thead><tbody class=planningbody>';
}

if(count($planningwithouttimeA) >0)
{
  if($iscalendarform || (!$iscalendarform && !$noresultwithtime))
  {
    echo '<tr class=noborder><td class=noborder></td>';
  }
	for($day=0;$day<$nbdays;$day++)
	{
	  echo '<td class=planningevents colspan=' .$COLBYDAYWHENDATE. '>';
	  if(isset($planningwithouttimeA[$day]))
	  {
		echo $planningwithouttimeA[$day];
	  }
	  echo '</td>';
	}
	echo '</tr>';	
}


#if($debug){echo "totallines=$totallines<br>";}
$linenb = -1;
for ($h=$starthour;$h<=$stophour;$h++)
{
  $mstart = 0;$mstop = 45;   
  if($h == $starthour) 
  { 
    $mstart = $startminutes; 
    if($h==$stophour) 
    {
      $mstop = $stopminutes;
    } 
  } 
  elseif($h == $stophour) 
  { 
    $mstop = $stopminutes; 
  }

  for($m=$mstart;$m<=$mstop;$m+=15)   
  { 
    if($m==0) {echo '<tr class=planninghours><td class=planningtitle>';}
    else{echo '<tr class=planningminutes><td class=planningtitleminutes>';}
    echo d_displayhourmin($h,$m) .'</td>'; 
    
    $linenb++; #linenumber 
    for($day=0;$day<$nbdays;$day++)
    {
      if($DEBUG){$temp = $num_resultsA[$day];$tempo = $noplanningA[$day];echo 'DAY=' . $day . '/nbresults='.$temp . '<br>';}
      
      if($num_resultsA[$day] > 0 && !isset($noplanningA[$day]))
      {
        for($col=0;$col<$nbcolA[$day];$col++)
        {         
          if (isset($titleA[$day][$linenb][$col])) { $title = $titleA[$day][$linenb][$col]; } else { $title = ''; }
          if (isset($colorA[$day][$linenb][$col])) { $color = $colorA[$day][$linenb][$col]; } else { $color = ''; }
          if($DEBUG){echo  'day:' . $day . ' linenb:' . $linenb. ' col:' . $col . ' title:' . $title . ' color:' . $color;      }
          if(!empty($title))
          {      
            $noresult = 0;
            if($col == 0){ echo '<td colspan=' . ($COLBYDAYWHENDATE - $nbcolA[$day]+1);}
            else { echo '<td';} 
            $rowspan = 1;
            #calculate rowspan: color go on but without new title
            while(empty($titleA[$day][$linenb+$rowspan][$col]) && !empty($colorA[$day][$linenb+$rowspan][$col]) && (($linenb + $rowspan) < $totallines))
            {
              $rowspan ++;
            }
            echo ' rowspan= '.$rowspan . ' class=planningcolor' . $color . '>' . $title . '</td>';          
          }
          else if (empty($colorA[$day][$linenb][$col]))
          {
            if($col == 0){ echo '<td class=borderleft colspan=' . ($COLBYDAYWHENDATE - $nbcolA[$day] +1);}
            else { echo '<td';}           
            echo ' class=noborder>&nbsp;</td>';
          }#else: already taken into account with rowspan
        }#for $col
      }#if ($num_resultsA[day] > 0 && !isset($noplanningA[$day]))
      else
      {
        echo '<td class=withborder colspan=' . $COLBYDAYWHENDATE . '>&nbsp;</td>';
      } 
    }#for $day
  }#for m
  echo '</tr>';
}#for $h      

if(1==1) # was !$noresult
{
  echo '</tbody></table></form>';
  if(!$iscalendarform)
  {
    if (isset($calendar_clientid) && $calendar_clientid > 0)
    {
      #
    }
    else
    {
      echo '</div><br>';
    }
  }
}
?>