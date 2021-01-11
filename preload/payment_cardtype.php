<?php
if (!isset($payment_cardtypeA))
{
  $query = 'select payment_cardtypeid,payment_cardtypename,deleted from payment_cardtype order by deleted,payment_cardtypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['payment_cardtypeid']+0);
    $payment_cardtypeA[$temp_id] = $query_result[$kladd_i]['payment_cardtypename'];
    $payment_cardtype_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>