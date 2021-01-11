<?php
# load $calendarA[$calendarid]
if (!isset($calendarA))
{
  $query = 'select calendarid,date,event,isbankholiday,deleted from calendar';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by date';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $calendarid_temp = (int) ($query_result[$kladd_i]['calendarid']+0);
    $calendarA[$calendarid_temp] = $query_result[$kladd_i]['event'];
    $calendar_dateA[$calendarid_temp] = $query_result[$kladd_i]['date'];
    $calendar_isbankholidayA[$calendarid_temp] = $query_result[$kladd_i]['isbankholiday'];
    $calendar_deletedA[$calendarid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>