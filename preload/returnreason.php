<?php
if (!isset($returnreasonA))
{
  $query = 'select returnreasonid,returnreasonname,returntostock,deleted from returnreason order by returnreasonname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $returnreasonid_temp = (int) ($query_result[$kladd_i]['returnreasonid']+0);
    $returnreasonA[$returnreasonid_temp] = $query_result[$kladd_i]['returnreasonname'];
    $returnreason_returntostockA[$returnreasonid_temp] = $query_result[$kladd_i]['returntostock'];
    $returnreason_deletedA[$returnreasonid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>