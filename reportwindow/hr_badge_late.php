<?php

require('preload/employee.php');

$PA['start1'] = 'time';
$PA['stop1'] = 'time';
$PA['start2'] = 'time';
$PA['stop2'] = 'time'; # HERE
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');

$time_limitA = array($start1,$stop1,$start2,$stop2);
if ($startdate == $stopdate) { $singledate = 1; }
else { $singledate = 0; }

$title = 'Rapport Retards '.datefix($startdate,'short').' à '.datefix($stopdate,'short');
showtitle_new($title);
echo d_table('report');

$query = 'select employeeid,badgedate,badgetime,badgeuserid,badgeusername
from badgelog
where deleted=0 and badgetime is not null and badgedate>=? and badgedate<=?
order by employeeid,badgedate,badgetime';
$query_prm = array($startdate, $stopdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if ($i == 0 || $query_result[$i]['employeeid'] != $query_result[($i-1)]['employeeid'])
  {
    if ($query_result[$i]['employeeid'] > 0)
    {
      echo d_tr(),d_td($employeeA[$query_result[$i]['employeeid']]);
      $counter = 0;
    }
  }
  if ($i == 0 || $query_result[$i]['badgedate'] != $query_result[($i-1)]['badgedate'] || $query_result[$i]['employeeid'] != $query_result[($i-1)]['employeeid'])
  {
    if ($singledate == 0 && $query_result[$i]['employeeid'] > 0)
    {
      echo d_td($query_result[$i]['badgedate'],'date');
      $counter = 0;
    }
  }
  if ($query_result[$i]['employeeid'] > 0)
  {
    $alert = '';
    if ($counter == 0 || $counter == 2)
    {
      if ($time_limitA[$counter] < substr($query_result[$i]['badgetime'],0,5)) { $alert = 'alert'; }
    }
    elseif ($counter == 1 || $counter == 3)
    {#echo '<br>comparing '.$time_limitA[$counter].' to '.substr($query_result[$i]['badgetime'],0,5);
      if ($time_limitA[$counter] > $query_result[$i]['badgetime']) { $alert = 'alert'; }
    }
    echo d_td(substr($query_result[$i]['badgetime'],0,5),$alert);
    $counter++;
  }
}
echo d_table_end();

?>