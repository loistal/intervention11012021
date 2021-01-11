<?php
if (!isset($clientaction_caseA))
{
  $query = 'select clientaction_caseid,casename,deleted from clientaction_case order by deleted,casename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['clientaction_caseid']+0);
    $clientaction_caseA[$id_temp] = $query_result[$kladd_i]['casename'];
    $clientaction_case_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>