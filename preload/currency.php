<?php
# load $currencyA[$currencyid]
if (!isset($currencyA))
{
  $query = 'select currencyid,currencyname,currencyacronym,deleted from currency order by deleted,currencyname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $currencyid_temp = (int) ($query_result[$kladd_i]['currencyid']+0);
    $currencyA[$currencyid_temp] = $query_result[$kladd_i]['currencyname'];
    $currency_acronymA[$currencyid_temp] = $query_result[$kladd_i]['currencyacronym'];
    $currency_deletedA[$currencyid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>