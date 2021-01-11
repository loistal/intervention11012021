<?php
if (!isset($invoice_priceoption1A))
{
  $query = 'select * from invoice_priceoption1 order by deleted,`rank`,invoice_priceoption1name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp_i=0;$temp_i<$num_results;$temp_i++)
  {
    $id_temp = (int) ($query_result[$temp_i]['invoice_priceoption1id']+0);
    $invoice_priceoption1A[$id_temp] = $query_result[$temp_i]['invoice_priceoption1name'];
    $invoice_priceoption1_deletedA[$id_temp] = $query_result[$temp_i]['deleted'];
    $invoice_priceoption1_salesprice_modA[$id_temp] = $query_result[$temp_i]['salesprice_mod'];
    $invoice_priceoption1_rankA[$id_temp] = $query_result[$temp_i]['rank'];
  }
}
?>