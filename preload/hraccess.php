<?php
# load $hraccessA[$hraccessid]

if (!isset($hraccessA))
{
  $query = 'select * from hraccess';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  } 
  $query .= ' order by hraccessname';  

  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $hraccessid_temp = (int) ($query_result[$kladd_i]['hraccessid']+0);
    $hraccessA[$hraccessid_temp] = $query_result[$kladd_i]['hraccessname'];
    $hraccess_ismanagerA[$hraccessid_temp] = $query_result[$kladd_i]['ismanager'];
    $hraccess_employeecategoryidA[$hraccessid_temp] = $query_result[$kladd_i]['employeecategoryid'];
    $hraccess_employeedepartmentidA[$hraccessid_temp] = $query_result[$kladd_i]['employeedepartmentid'];
    $hraccess_employeesectionidA[$hraccessid_temp] = $query_result[$kladd_i]['employeesectionid'];
    $hraccess_ispersoinfosaccessA[$hraccessid_temp] = $query_result[$kladd_i]['ispersoinfosaccess'];
    $hraccess_isdisciplinaryfileaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isdisciplinaryfileaccess'];
    $hraccess_isannualinterviewaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isannualinterviewaccess'];
    $hraccess_isplanningaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isplanningaccess'];
    $hraccess_isplanningteamaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isplanningteamaccess'];
    $hraccess_isreportaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isreportaccess'];
    $hraccess_istrainingaccessA[$hraccessid_temp] = $query_result[$kladd_i]['istrainingaccess'];
    $hraccess_istravelexpensesaccessA[$hraccessid_temp] = $query_result[$kladd_i]['istravelexpensesaccess'];
    $hraccess_isdailycheckingaccessA[$hraccessid_temp] = $query_result[$kladd_i]['isdailycheckingaccess'];
    $hraccess_ispayrollaccessA[$hraccessid_temp] = $query_result[$kladd_i]['ispayrollaccess'];
    $hraccess_isbadgemanualentryaccess[$hraccessid_temp] = $query_result[$kladd_i]['isbadgemanualentryaccess'];
    $hraccess_istrainingbudgetaccess[$hraccessid_temp] = $query_result[$kladd_i]['istrainingbudgetaccess'];
    $hraccess_ismedicalaccess[$hraccessid_temp] = $query_result[$kladd_i]['ismedicalaccess'];
    $hraccess_deletedA[$hraccessid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>