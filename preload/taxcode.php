<?php
# load $taxcodeA[$taxcodeid]
if (!isset($taxcodeA))
{
  $query = 'select taxcodeid,taxcode,deleted from taxcode order by deleted,taxcode';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $taxcodeid_temp = (int) ($query_result[$kladd_i]['taxcodeid']+0);
    $taxcodeA[$taxcodeid_temp] = $query_result[$kladd_i]['taxcode']+0; # tax rate in %
    $taxcode_deletedA[$taxcodeid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>