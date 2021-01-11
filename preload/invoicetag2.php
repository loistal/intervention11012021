<?php
if (!isset($invoicetag2A))
{
  $query = 'select invoicetag2id,invoicetag2name,deleted,daysaddedtocustomdate from invoicetag2 order by deleted,invoicetag2name';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $invoicetag2id_temp = (int) ($query_result[$kladd_i]['invoicetag2id']+0);
    $invoicetag2A[$invoicetag2id_temp] = $query_result[$kladd_i]['invoicetag2name'];
    $invoicetag2_deletedA[$invoicetag2id_temp] = $query_result[$kladd_i]['deleted'];
    $invoicetag2_daysaddedtocustomdateA[$invoicetag2id_temp] = $query_result[$kladd_i]['daysaddedtocustomdate'];
  }
}
?>