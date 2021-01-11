<?php
if (!isset($invoicetagA))
{
  $query = 'select * from invoicetag order by deleted,invoicetagname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $invoicetagid_temp = (int) ($query_result[$kladd_i]['invoicetagid']+0);
    $invoicetagA[$invoicetagid_temp] = $query_result[$kladd_i]['invoicetagname'];
    $invoicetag_clientidA[$invoicetagid_temp] = $query_result[$kladd_i]['invoicetag_clientid'];
    $invoicetag_deletedA[$invoicetagid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>