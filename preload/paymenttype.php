<?php
# load $paymenttypeA[$paymenttypeid]
if (!isset($paymenttypeA))
{
  $query = 'select paymenttypeid,paymenttypename from paymenttype order by paymenttypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $paymenttypeid_temp = (int) ($query_result[$kladd_i]['paymenttypeid']+0);
    $paymenttypeA[$paymenttypeid_temp] = $query_result[$kladd_i]['paymenttypename'];
  }
}
?>