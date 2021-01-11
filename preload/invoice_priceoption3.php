<?php
if (!isset($invoice_priceoption3A))
{
  $query = 'select * from invoice_priceoption3 order by deleted,`rank`,invoice_priceoption3name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp_i=0;$temp_i<$num_results;$temp_i++)
  {
    $id_temp = (int) ($query_result[$temp_i]['invoice_priceoption3id']+0);
    $invoice_priceoption3A[$id_temp] = $query_result[$temp_i]['invoice_priceoption3name'];
    $invoice_priceoption3_deletedA[$id_temp] = $query_result[$temp_i]['deleted'];
    $invoice_priceoption3_salesprice_modA[$id_temp] = $query_result[$temp_i]['salesprice_mod'];
    $invoice_priceoption3_rankA[$id_temp] = $query_result[$temp_i]['rank'];
  }
}
?>