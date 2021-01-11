<?php

function startswith($hay, $needle) {
  return mb_substr($hay, 0, strlen($needle)) === $needle;
}

function endswith($hay, $needle) {
  return mb_substr($hay, -strlen($needle)) === $needle;
}

function d_hourswithout0($time)
{
  $hour = d_hours($time);
  if(startswith($hour,'0')){$hour = mb_substr($hour,1,1);}
  return $hour;
}

function d_hours($time)
{
  if(isset($time) && mb_stripos($time,':') >= 0 )
  {
    return mb_substr($time,0,mb_stripos($time,':'));
  }
  else return '';
}

function d_minutes($time)
{
  $posmin = mb_stripos($time,':');
  if(isset($time) && $posmin >= 0)
  {
    ##hh:mm:ss
    $len = mb_strlen($time);   
    if($posmin+2 < $len-1) 
    {
      $possec = mb_stripos($time,':',$posmin+1);
      $len = $possec;
    }
    return mb_substr($time,mb_stripos($time,':')+1,$len - ($posmin+1));
  }
  else return '';
}  

function d_minuteswithout0($time)
{
  $minutes = d_minutes($time);
  if(startswith($minutes,'0')){$minutes = mb_substr($minutes,1,1);}
  return $minutes;
}  

function d_displayhourmin($h,$m)
{
  $display = '';
  $h += 0;
  $m += 0;
  if($h < 10)
  {
    if($m < 10)
    {
      return d_trad('hourbefore10:minbefore10',array($h,$m)); 
    }
    else
    {
      return d_trad('hourbefore10:min',array($h,$m)); 
    }
  }
    elseif($m < 10)
    { 
      return d_trad('hour:minbefore10',array($h,$m));
    }
    else
    { 
      return d_trad('hour:min',array($h,$m));
    }
}

function d_displayhourmin_24h($h,$m)
{
  $display = '';
  $h += 0;
  $m += 0;
  if($h < 10)
  {
    if($m < 10)
    {
      return '0' . $h . ':' . '0' . $m; 
    }
    else
    {
      return '0' . $h . ':'  . $m; 
    }
  }
  elseif($m < 10)
  { 
    return $h . ':' . '0' . $m; 
  }
  else
  { 
    return $h . ':'  . $m; 
  }
}

function d_displaytime($time)
{
  return d_displayhourmin(d_hours($time),d_minutes($time));
}

function d_displaytimeinterval($timestart,$timestop,$withbracket=0)
{
  $display = '';
  if($timestart != NULL)
  {
    if($withbracket){$display = ' (';}	
    $display .= d_hours($timestart) . ':' . d_minutes($timestart);
    if($timestop != NULL)
    {
      $display .= '-' . d_hours($timestop) . ':' . d_minutes($timestop);
    }	
    if($withbracket){$display .= ') ';}
  }
  return $display;
}

function d_getdayofweek($date)
{
  #date like YYYY-MM-DD
  $dateday = mb_substr($date,8,2);
  $datemonth = mb_substr($date,5,2);
  $dateyear = mb_substr($date,0,4);
  $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
  return date(N,$datetimestamp);
} 


function d_issunday($date)
{
  #date like YYYY-MM-DD
  $dateday = mb_substr($date,8,2);
  $datemonth = mb_substr($date,5,2);
  $dateyear = mb_substr($date,0,4);
  $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
  $dayofweek = date(N,$datetimestamp);
  if ($dayofweek == 7) { return 1;}
  else { return 0;}
}

function d_getmonday($week,$year)
{
  $monday = new DateTime();
  $monday->setISOdate($year, $week);
  return $monday->format('Y-m-d');
}

function d_getmonday_todisplay($week,$year)
{
  return datefix2(d_getmonday($week,$year));
}

function d_getsunday($week,$year)
{
  $sunday = new DateTime();  
  $sunday->setISOdate($year, $week);
  #add 6 days to get sunday              
  $interval = new DateInterval('P6D');
  $sunday->add($interval);
  return $sunday->format('Y-m-d');
}

function d_getdayafter($date)
{
  $datetime = new DateTime($date);
  #Plus 1 day
  $interval = new DateInterval('P1D');
  $datetime->add($interval);
  return $datetime->format('Y-m-d');
}  

function d_numdays($startdate, $stopdate) 
{
  $startdatetime = new DateTime($startdate);
  $stopdatetime = new DateTime($stopdate);
  $diff = $startdatetime->diff($stopdatetime);
  return  $diff->format('%a') + 1;
}

function d_getsunday_todisplay($week,$year)
{
  return datefix2(d_getsunday($week,$year)); 
}  

function d_getday($dayofweek,$week,$year)
{
  $day = new DateTime();  
  $day->setISOdate($year, $week);
  #add $dayofweek-1 days  
  $int = 'P' . ($dayofweek-1) . 'D';
  $interval = new DateInterval($int);
  $day->add($interval);
  return $day->format('Y-m-d');
}

function d_getdateadddays($nbdays,$date)
{
  #add $nbdays days 
  if($nbdays == 0) { return $date;}    
  $int = 'P' . $nbdays . 'D';
  $interval = new DateInterval($int);
  $datetime = DateTime::createFromFormat('Y-m-d', $date);
  $datetime->add($interval);
  return $datetime->format('Y-m-d');
}  

function d_getdateaddweeks($nbweeks,$month,$year)
{
  #find the last day of lastmonth
  $date = d_getfirstdayofmonthdatetime($month,$year);
  #sub $nbweeks days 
  $nbdays = $nbweeks * 7;		
  if($nbdays == 0) { return $date->format('Y-m-d');}    
  $int = 'P' . $nbdays . 'D';
  $interval = new DateInterval($int);
  $date->add($interval);
  return $date->format('Y-m-d');		
}

function d_getdatesubstdays($nbdays,$date)
{
  #sub $nbdays days  
  $int = 'P' . $nbdays . 'D';
  $interval = new DateInterval($int);
  $date->sub($interval);
  return $date->format('Y-m-d');
}

function d_getdateaddmonths($nbmonths,$date)
{
  #add $nbmonths months  
  $newdate = $date;
  if ($nbmonths > 0)
  {    
    $int = 'P' . ($nbmonths) . 'M';
    $interval = new DateInterval($int);
    $date->add($interval);
  }
  return $newdate->format('Y-m-d');
}

function d_getdatesubstmonths($nbmonths,$date)
{
  #add $nbmonths months  
  $newdate = $date;
  if ($nbmonths > 0)
  {      
    $int = 'P' . ($nbmonths) . 'M';
    $interval = new DateInterval($int);
    $date->sub($interval);
  }
  return $newdate->format('Y-m-d');    
}

function d_getdatesubstractdays($nbdays,$date)
{
  #substract $nbdays days  
  $int = 'M' . $nbdays . 'D';
  $interval = new DateInterval($int);
  $date->add($interval);
  return $date->format('Y-m-d');
} 

function d_getdaytodisplay($dayofweek,$week,$year)
{
  return datefix2(d_getday($dayofweek,$week,$year)->format('Y-m-d'));
} 

function d_getfirstdayofmonth($month,$year)
{
  $day = new DateTime();  
  $day->setDate($year, $month, 1);
  return $day->format('Y-m-d');
}  

function d_getfirstdayofnextmonth($month,$year)
{
  $day = new DateTime();  
  $nextmonth = $month +1;$nextyear = $year;
  if ($nextmonth == 13){ $nextmonth = 1; $nextyear += 1;}		
  $day->setDate($nextyear, $nextmonth, 1);
  return $day->format('Y-m-d');
}

function d_getfirstdayofmonthdate($month,$year)
{
  $day = new DateTime();  
  $day->setDate($year, $month, 1);
  return $day->getTimeStamp();
}  

function d_getfirstdayofmonthdayofweek($month,$year)
{
  $datetimestamp = mktime(0,0,0,$month,1,$year);
  return date(N,$datetimestamp);
}  	

function d_getfirstdayofmonthdatetime($month,$year)
{
  $date = new DateTime();  
  return $date->setDate($year, $month, 1);
}    

function d_getfirstdayoflastmonthdate($month,$year)
{
  $date = new DateTime();
  $lastmonth = $month -1;$lastyear = $year;
  if ($lastmonth == 0){ $lastmonth = 12; $lastyear -= 1;}
  $date->setDate($lastyear, $lastmonth, 1);	
  return $date;
}  

function d_getlastdayofmonthdate($month,$year)
{
  $date = new DateTime();  
  $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $date = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' .$day);		
  return $date;
}

function d_getlastdayofmonthdayofweek($month,$year)
{
  $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $datetimestamp = mktime(0,0,0,$month,$day,$year);
  return date(N,$datetimestamp);
}  
  
function d_displayhourminfrommin($minutes,$isdisplaynothing=1)
{
  #display hh:mm
  #modulo => minutes to be displayed
  $m = $minutes % 60;

  #hours to be displayed
  $h = round(floatval(($minutes-$m) / 60)); 
  
  #display hours and minutes
  if ((($h > 0) || ($m >0)) || ($isdisplaynothing == 1))
  {
    return d_displayhourmin_24h($h,$m);
  }
  else
  {
    return '-';
  }
}

function d_displayhourminfrommincsv($minutes,$isdisplaynothing=1)
{
  return '\'' . d_displayhourminfrommin($minutes,$isdisplaynothing) . '\'';
}


function d_displayovertime($overtime)
{
  if( strpos($overtime,'-') === FALSE)
  {
    return '+' . d_displayhourminfrommin($overtime);
  }
  else
  {
    $overtime = substr($overtime,1);  
    return '-' . d_displayhourminfrommin($overtime);    
  }
}

 function d_displayovertimecsv($overtime)
{
  return '\'' . d_displayovertime($overtime) . '\'';    
}

function d_gethour($time)
{    
  $array_hour = explode(":",$time);
  $hour = $array_hour[0];
  #return 8 instead of 08
  if ( strpos($hour,'0') === 0) { $hour = mb_substr($hour,1);}
  return $hour;    
}

function d_timetominutes($time,$issecond=0)
{    
  $array_hour = explode(":",$time);
  $secondes = (3600 * $array_hour[0]) + (60 * $array_hour[1]);    

  #if there are secondes
  if( $issecond == 1)
  {
    $secondes += $array_hour[2];
  }
  $minutes = floatval($secondes / 60) ;
  
  return $minutes;    
}

function d_overtimetominutes($overtime)
{    
  $isnegative = 0;
  if( strpos($overtime,'-') === 0)
  {
    $isnegative = 1;            
  }
  #delete +/-
  $time = mb_substr($overtime,1);  
  
  $minutes = d_timetominutes($time);
  
  if($isnegative)
  {
    return '-' . $minutes;
  }
  else
  {
    return $minutes;
  }    
}

function d_checkovertime($time)
{
  #accept between -99:59 and 99:59
  return preg_match("#([+]{1}[0-9]{1}[0-9]{1}:[0-5]{1}[0-9]{1})|([-]{1}[0-9]{1}[0-9]{1}:[0-5]{1}[0-9]{1})#", $time);
} 

function d_getnbweeksinyear($year) 
{
  $numweek = 1;
  $i = 0;
  #get the last week before 1rst one of next year
  while ($numweek == 1) 
  {
    #get 31 of december date
    $timestamp = mktime(0,0,0,12,31-$i,$year);
 
    #get the week number
    $numweek = date(W,$timestamp);
    
    $i ++;
  }
  return $numweek;
}  

function d_addminutestotime($time,$minutestobeadded)
{
  $newtime = $time;
  $minutes = d_timetominutes($time) + $minutestobeadded;
  return d_displayhourminfrommin($minutes);
}

function d_subtractminutestotime($time,$minutestobesubtracted)
{
  $newtime = $time;
  $minutes = d_timetominutes($time) - $minutestobesubtracted;
  return d_displayhourminfrommin($minutes);
}

#calculatehow many minutes are made by night
function d_getminutesnight($timestop,$timestart,$NIGHT_STOP_TIME,$NIGHT_START_TIME)
{
  $NIGHT_STOP_HOUR = d_gethour($NIGHT_STOP_TIME);
  $NIGHT_START_HOUR = d_gethour($NIGHT_START_TIME);
  
  #night hours
  $hourstop = d_gethour($timestop);        
  $hourstart = d_gethour($timestart);       
  $minutesnight = 0;
  #if employee starts working before dawn
  if ($hourstart < $NIGHT_STOP_HOUR )
  {
    # the whole working time is during night, before dawn
    if ($hourstop < $NIGHT_STOP_HOUR)
    {
      $minutesnight = (d_timetominutes($timestop) - d_timetominutes($timestart));    
    }
    else
    {
      $minutesnight = (d_timetominutes($NIGHT_STOP_TIME) - d_timetominutes($timestart));              
    }
  }
  #if employee stop working at night
  if ($hourstop > $NIGHT_START_HOUR)
  {
    if($hourstart > $NIGHT_START_HOUR)
    {
      $minutesnight += (d_timetominutes($timestop) - d_timetominutes($timestart));            
    }
    else
    {
      $minutesnight += (d_timetominutes($timestop) - d_timetominutes($NIGHT_START_TIME));          
    }
  }  
  return $minutesnight;
}

?>