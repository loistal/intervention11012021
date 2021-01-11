<?php
if (!isset($invoice_priceoption2_filterA))
{
  $query = 'select * from invoice_priceoption2_filter order by deleted,invoice_priceoption2_filtername';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp=0;$temp<$num_results;$temp++)
  {
    $id_temp = (int) ($query_result[$temp]['invoice_priceoption2_filterid']+0);
    $invoice_priceoption2_filterA[$id_temp] = $query_result[$temp]['invoice_priceoption2_filtername'];
    $invoice_priceoption2_filter_deletedA[$id_temp] = $query_result[$temp]['deleted'];
  }
}
unset($temp,$id_temp);
?>