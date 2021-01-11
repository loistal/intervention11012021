<?php
if (!isset($accountingnumberA))
{
  $query = 'select accountingnumberid,acnumber,acname,needreference,isbank,matchable,deleted,accountinggroupid from accountingnumber';
  $query .= ' order by deleted,acnumber,accountingnumberid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['accountingnumberid']+0);
    $accountingnumberA[$id_temp] = $query_result[$kladd_i]['acnumber'];
    $accountingnumber_acnameA[$id_temp] = $query_result[$kladd_i]['acname'];
    $accountingnumber_longA[$id_temp] = $query_result[$kladd_i]['acnumber'] . ' '. $query_result[$kladd_i]['acname'];
    if ($query_result[$kladd_i]['deleted'] == 1) { $accountingnumber_longA[$id_temp] = $accountingnumber_longA[$id_temp] . ' [Supprimé]'; }
    $accountingnumber_needreferenceA[$id_temp] = $query_result[$kladd_i]['needreference'];
    $accountingnumber_isbankA[$id_temp] = $query_result[$kladd_i]['isbank'];
    $accountingnumber_matchableA[$id_temp] = $query_result[$kladd_i]['matchable'];
    $accountingnumber_accountinggroupidA[$id_temp] = $query_result[$kladd_i]['accountinggroupid'];
    $accountingnumber_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>