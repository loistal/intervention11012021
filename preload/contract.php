<?php
# load $contractA[$contractid]
if (!isset($contractA))
{
  $query = 'select * from contract';
  if(!$_SESSION['ds_showdeleteditems'])
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by contractname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $contractid_temp = (int) ($query_result[$kladd_i]['contractid']+0);
    $contractA[$contractid_temp] = $query_result[$kladd_i]['contractname'];
    $contract_deletedA[$contractid_temp] = $query_result[$kladd_i]['deleted'];
    $contract_salaried_exemptA[$contractid_temp] = $query_result[$kladd_i]['salaried_exempt'];
  }
}
?>