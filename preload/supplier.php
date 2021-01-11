<?php
# load $supplierA[$clientid]
if (!isset($supplierA))
{
  $query = 'select clientid,clientname from client where issupplier=1 order by clientname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $supplierid_temp = (int) ($query_result[$kladd_i]['clientid']+0);
    $supplierA[$supplierid_temp] = $query_result[$kladd_i]['clientname'];
  }
  unset($supplierid_temp,$kladd_i);
}
?>