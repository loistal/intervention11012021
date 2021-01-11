<?php
##IN : $date $day $month $year $dayofweek $ds_planningteamnbvalues $eid for employeeid
##OUT: $hastobeworked = number of part a day who has to be worked
#check if there is a bank holiday for this date

$STATE_SAVED = 9;
$STATE_SUBMITED = 0;
$STATE_ACCEPTED = 1;
$STATE_REFUSED = 2;

$query = 'select isbankholiday from calendar where date = ? and deleted=0';
$query_prm = array($date);
require('inc/doquery.php');
$isbankholiday = 0;
if ($num_results > 0)
{ 
  $isbankholiday = $query_result[0]['isbankholiday'];  
}

#check if each part of this day must be worked
$hastobeworked = $ds_planningteamnbvalues;
if (!$isbankholiday )
{
  #verify if this day is already validated
  $query = 'select * from planningteamvalidation pt where pt.deleted = 0 and pt.employeeid= ? and pt.planningdate=? and pt.validated=1';
  $query_prm = array($employeeid,$date); 
  require('inc/doquery.php');
  
  if ($num_results > 0)
  {
    for($c=1;$c<=$ds_planningteamnbvalues;$c++)
    {
      $ptvalueid = $query_result[0]['planningteamvalueid' . $c];
      if( $ptvalueid > 0)
      {
        $ispresence = $planningteamvalue_presenceA[$ptvalueid];
        if ($ispresence == 0) {$hastobeworked -= 1;}      
      }
    }
  }
  else
  {
    #for each part of day (AM/PM for example)
    for($c=1;$c<=$ds_planningteamnbvalues;$c++)
    {
      #1- record only for this date like attendance/absence or yearly
      $query = 'select * from planningteam pt,planningteamvalue pv where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ')';
      #only presence day
      $query .= ' and pt.planningteamvalueid' .$c .' = pv.planningteamvalueid and pv.presence = 0';
      $query .= ' and (( pt.periodic = 0 and pt.planningdate=? ) or (pt.periodic = 3 and DAY(pt.planningdate) = ? and MONTH(pt.planningdate) = ?))';
      $query_prm = array($eid,$date,$day,$month);
      require('inc/doquery.php');
      if($num_results > 0)
      {
        $hastobeworked -= 1;
      }
      else
      {   
        #2- record for a period like vacations/ sick leave
        $query = 'select * from planningteam pt,planningteamvalue pv where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ') and (pt.planningstart <= ? and pt.planningstop >= ?)';   
        #only presence day
        $query .= ' and pt.planningteamvalueid' .$c .' = pv.planningteamvalueid and pv.presence = 0';           
        $query_prm = array($eid,$date,$date);  
        require('inc/doquery.php');
        if($num_results > 0)
        {
          $hastobeworked -= 1;
        }
        else
        {   
          #3- record for a month period: will take the record with biggest planning_spec id to display
          $query = 'select * from planningteam pt,planningteamvalue pv where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ')';
          $query_prm = array($eid);  
          #only presence day
          $query .= ' and pt.planningteamvalueid' .$c .' = pv.planningteamvalueid and pv.presence = 0';           
          
          $query .= ' and (pt.periodic = 2 and DAY(pt.planningdate) = ?';array_push($query_prm,$day);     
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
            $hastobeworked -= 1;
          }
          else
          {   
            #4- record for a week: : will take the record with biggest planning_spec id to display
            $query = 'select * from planningteam pt,planningteamvalue pv where pt.employeeid=? and pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED  . ')';
            $query_prm = array($eid); 
            #only presence day
            $query .= ' and pt.planningteamvalueid' .$c .' = pv.planningteamvalueid and pv.presence = 0';           
            
            $query .= ' and (pt.periodic = 1 and pt.dayofweek = ?';array_push($query_prm,$dayofweek);  
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
                if($day >=1 && $day <=7)
                {
                  #every 1st week of month
                  $query .= ' or (pt.periodic_spec=3)';
                }
                else if($day >=8 && $day <=14)
                {
                  #every 2nd week of month
                  $query .= ' or (pt.periodic_spec=4)';
                }
                else if($day >=15 && $day <=21)
                {     
                  #every 3rd week of month
                  $query .= ' or (pt.periodic_spec=5)';
                }
                else if($day >=22 && $day <=31)
                {  
                  #every 4th week of month
                  $query .= ' or (pt.periodic_spec=6)';  
                }
              $query .= ')';
            $query .= ') order by pt.periodic_spec desc';

            require('inc/doquery.php');
            if($num_results > 0)
            {
              $hastobeworked -= 1;  
            }
          }//if #3 no result
        }//if #2 no result
      }//if #1 no result
      unset($query,$query_prm,$num_results,$query_result);
    }
  }
}
    
?>