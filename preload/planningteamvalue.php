<?php
# load $planningteamvalueA[$planningteamvalueid]
# $hr_orderby_presence = 1 if order by absence,rest, rank else absence desc,rest desc, rank
# $hr_orderby_absence = 1 if order by absence desc,rest desc, rank
# default order by rank
if (!isset($hr_orderby_presence)) { $hr_orderby_presence = 0;}
if (!isset($hr_orderby_absence)) { $hr_orderby_absence = 0;}

if (!isset($planningteamvalueA))
{
  $query = 'select * from planningteamvalue';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  } 
  if ($hr_orderby_presence === 1)
  {
    $query .= ' order by absence,rest, rank';
  }
  else if ($hr_orderby_absence === 1)
  {
    $query .= ' order by ispaidleave desc,absence desc,rest desc, rank';
  }
  else
  {
    $query .= ' order by rank';  
  }
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $planningteamvalueid_temp = (int) ($query_result[$kladd_i]['planningteamvalueid']+0);
    $planningteamvalueA[$planningteamvalueid_temp] = $query_result[$kladd_i]['planningteamvaluename'];
    $planningteamvalue_symbolA[$planningteamvalueid_temp] = $query_result[$kladd_i]['planningteamvaluesymbol'];
    $planningteamvalue_coloridA[$planningteamvalueid_temp] = $query_result[$kladd_i]['colorid'];
    $planningteamvalue_colorid1A[$planningteamvalueid_temp] = $query_result[$kladd_i]['colorid1'];
    $planningteamvalue_colorid2A[$planningteamvalueid_temp] = $query_result[$kladd_i]['colorid2'];
    $planningteamvalue_presenceA[$planningteamvalueid_temp] = $query_result[$kladd_i]['presence'];
    $planningteamvalue_absenceA[$planningteamvalueid_temp] = $query_result[$kladd_i]['absence'];
    $planningteamvalue_restA[$planningteamvalueid_temp] = $query_result[$kladd_i]['rest'];
    $planningteamvalue_rankA[$planningteamvalueid_temp] = $query_result[$kladd_i]['rank'];
    $planningteamvalue_ispaidleaveA[$planningteamvalueid_temp] = $query_result[$kladd_i]['ispaidleave'];
    $isbankholiday_temp = $planningteamvalue_isbankholidayA[$planningteamvalueid_temp] = $query_result[$kladd_i]['isbankholiday'];
    $planningteamvalue_istrainingA[$planningteamvalueid_temp] = $query_result[$kladd_i]['istraining'];
    $planningteamvalue_deletedA[$planningteamvalueid_temp] = $query_result[$kladd_i]['deleted'];
    
    #save bank holiday planningteamvalueid in session
    if ($isbankholiday_temp == 1)
    {
      $_SESSION['ds_bankholidayplanningteamvalueid'] = $planningteamvalueid_temp;
    }
  }
}

unset($hr_orderby_absence,$hr_orderby_presence);
?>