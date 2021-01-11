<?php
# load $bankaccountA[$bankaccountid]
if (!isset($bankaccountA))
{
  $query = 'select bankaccountid,bankaccountname,bankid,accountingnumberid,deleted from bankaccount order by deleted,`rank`,bankaccountname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $bankaccountid_temp = (int) ($query_result[$kladd_i]['bankaccountid']+0);
    $bankaccountA[$bankaccountid_temp] = $query_result[$kladd_i]['bankaccountname'];
    $bankaccount_bankidA[$bankaccountid_temp] = $query_result[$kladd_i]['bankid'];
    $bankaccount_accountingnumberidA[$bankaccountid_temp] = $query_result[$kladd_i]['accountingnumberid'];
    $bankaccount_deletedA[$bankaccountid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>