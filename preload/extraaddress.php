<?php
# load $extraaddressA[$extraaddressid]
if (!isset($extraaddressA))
{
  $query = 'select extraaddressid,quarter,townid from extraaddress';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $extraaddressid_temp = (int) ($query_result[$kladd_i]['extraaddressid']+0);
    $extraaddress_quarterA[$extraaddressid_temp] = $query_result[$kladd_i]['quarter'];
    $extraaddress_townidA[$extraaddressid_temp] = $query_result[$kladd_i]['townid'];
  }
}
?>