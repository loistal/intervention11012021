<?php
if (!isset($bankA))
{
  $query = 'select bankid,bankname,fullbankname,deleted from bank order by deleted,bankname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $bankid_temp = (int) ($query_result[$kladd_i]['bankid']+0);
    $bankA[$bankid_temp] = $query_result[$kladd_i]['bankname'];
    $bank_fullbanknameA[$bankid_temp] = $query_result[$kladd_i]['fullbankname'];
    $bank_deletedA[$bankid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>