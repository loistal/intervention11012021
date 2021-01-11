<?php
if (!isset($reason_payment_modifyA))
{
  $query = 'select reason_payment_modifyid,reason_payment_modifyname,deleted from reason_payment_modify order by deleted,reason_payment_modifyname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['reason_payment_modifyid']+0);
    $reason_payment_modifyA[$id_temp] = $query_result[$kladd_i]['reason_payment_modifyname'];
    $reason_payment_modify_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>