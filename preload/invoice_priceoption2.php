<?php
if (!isset($invoice_priceoption2A))
{
  $query = 'select * from invoice_priceoption2 order by deleted,`rank`,invoice_priceoption2name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp_i=0;$temp_i<$num_results;$temp_i++)
  {
    $id_temp = (int) ($query_result[$temp_i]['invoice_priceoption2id']+0);
    $invoice_priceoption2A[$id_temp] = $query_result[$temp_i]['invoice_priceoption2name'];
    $invoice_priceoption2_deletedA[$id_temp] = $query_result[$temp_i]['deleted'];
    $invoice_priceoption2_salesprice_modA[$id_temp] = $query_result[$temp_i]['salesprice_mod'];
    $invoice_priceoption2_rankA[$id_temp] = $query_result[$temp_i]['rank'];
  }
}
?>