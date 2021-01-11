<?php
# load $deliverytypeA[$deliverytypeid]
if (!isset($deliverytypeA))
{
  $query = 'select deliverytypeid,deliverytypename,requirepayment from deliverytype order by deliverytypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $deliverytypeid_temp = (int) ($query_result[$kladd_i]['deliverytypeid']+0);
    $deliverytypeA[$deliverytypeid_temp] = $query_result[$kladd_i]['deliverytypename'];
    $deliverytype_requirepaymentA[$deliverytypeid_temp] = $query_result[$kladd_i]['requirepayment'];
    $deliverytype_deletedA[$deliverytypeid_temp] = 0;
  }
}
?>