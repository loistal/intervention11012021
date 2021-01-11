<?php
# load $paymentcategoryA[$paymentcategoryid]
if (!isset($paymentcategoryA))
{
  $query = 'select paymentcategoryid,paymentcategoryname,deleted from paymentcategory order by deleted,paymentcategoryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $paymentcategoryid_temp = (int) ($query_result[$kladd_i]['paymentcategoryid']+0);
    $paymentcategoryA[$paymentcategoryid_temp] = $query_result[$kladd_i]['paymentcategoryname'];
    $paymentcategory_deletedA[$paymentcategoryid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>