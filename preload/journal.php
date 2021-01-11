<?php
if (!isset($journalA))
{
  $query = 'select journalid,journalname,deleted from journal order by deleted,journalname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['journalid']+0);
    $journalA[$id_temp] = $query_result[$kladd_i]['journalname'];
    $journal_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>