<?php
#input $hr_statetoexcludeA: planningteam states to be excluded from request 
#output $row => first result for this query

#transform $hr_statetoexcludeA in a "not in" list
$hr_statetoexclude = '()';
if (isset($hr_statetoexcludeA)) 
{ 
  $hr_statetoexclude = '(';
  foreach($hr_statetoexcludeA as $id=>$stateid)
  {
    $hr_statetoexclude .= $stateid . ',' ;
  }
  #if query endswith ',' : delete it
  $poscoma = strripos($hr_statetoexclude,',');
  if ( $poscoma > 0 )
  {
    $hr_statetoexclude = substr($hr_statetoexclude,0,$poscoma);
  }
  $hr_statetoexclude .= ')';  
}

#for each day of period
for($day=1;$day<=$nbdays;$day++)
{
  if ( $period == $PERIOD_WEEK ) 
  {
    $date = d_getday($day,$week,$year); 
  }
  else if ( $ismonthperiod )
  {
    $datetime = new DateTime();
    $datetime->setDate($year, $month, $day);
    $date = $datetime->format('Y-m-d');
  }
  if(!empty($date))
  {
    $dateday = mb_substr($date,8,2);
    $datemonth = mb_substr($date,5,2);
    $datetimestamp = mktime(0,0,0,$datemonth,$dateday,$dateyear);
    $dayofweek = date(N,$datetimestamp);
    $dateday = date(j,$datetimestamp);    
    $month = date(n,$datetimestamp);
    $year = date(Y,$datetimestamp);
  } 
 
  #1- record only for this date like attendance/absence or yearly
  $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state not in ' . $hr_statetoexclude . ' and ';
  $query .= '(( pt.periodic = 0 and pt.planningdate=? ) or (pt.periodic = 3 and DAY(pt.planningdate) = ? and MONTH(pt.planningdate) = ?))';
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
  $query_prm = array($employeeid,$date,$dateday,$month,$planningteamvalueid);
  require('inc/doquery.php');
  if($num_results > 0)
  {
    $nbdaysorhalfdays ++;
  }
  else
  {   
    #2- record for a period like vacations/ sick leave
    $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state not in ' . $hr_statetoexclude . ' and (pt.planningstart <= ? and pt.planningstop >= ?)';   
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
    $query_prm = array($employeeid,$date,$date,$planningteamvalueid);  
    require('inc/doquery.php');
    if($num_results > 0)
    {
      $nbdaysorhalfdays ++;
    }
    else
    {   
      #3- record for a month period: will take the record with biggest planning_spec id to display
      $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state not in ' . $hr_statetoexclude;
      $query_prm = array($employeeid);  
      switch ($pv)
      { 
        case 1:
          $query .= ' and planningteamvalueid1 = ? and ';   
          break;
        case 2:
          $query .= ' and planningteamvalueid2 = ? and ';   
          break;
        case 3:
          $query .= ' and planningteamvalueid3 = ? and ';   
          break;  
      }    
      array_push($query_prm,$planningteamvalueid);   
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
        $nbdaysorhalfdays ++;
      }
      else
      {   
        #4- record for a week: : will take the record with biggest planning_spec id to display
        $query = 'select * from planningteam pt where pt.employeeid=? and pt.deleted=0 and pt.state not in ' . $hr_statetoexclude;
        $query_prm = array($employeeid); 
        switch ($pv)
        { 
          case 1:
            $query .= ' and planningteamvalueid1 = ? and ';   
            break;
          case 2:
            $query .= ' and planningteamvalueid2 = ? and ';   
            break;
          case 3:
            $query .= ' and planningteamvalueid3 = ? and ';   
            break;  
        }     
        array_push($query_prm,$planningteamvalueid);  
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
          $nbdaysorhalfdays ++;      
        }
      }//if #3 no result
    }//if #2 no result
  }//if #1 no result
}
unset($query,$query_prm,$num_results,$query_result);

?>